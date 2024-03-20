<?php


namespace App\Repositories\Transaction;


use App\Events\SendNotification;
use App\Exceptions\NotEnoughMoneyException;
use App\Exceptions\IdleServiceException;
use App\Exceptions\TransactionDeniedException;
use App\Models\Shopkeeper;
use App\Models\Transactions\Transaction;
use App\Models\Transactions\Wallet;
use App\Models\User;
use App\Services\MockyService;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\InvalidDataProviderException;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\DatabaseManager;


class TransactionRepository
{
    /**
     * @var DatabaseManager;
     */
    protected $dbManager;

    /**
     * @var Transaction;
     */
    protected $transactionModel;

    public function __construct(DatabaseManager $dbManager, Transaction $transactionModel)
    {
        $this->dbManager = $dbManager;
        $this->transactionModel = $transactionModel;
    }

    public function handle(array $data): Transaction
    {
        if (!$this->guardCanTransfer()) {
            throw new TransactionDeniedException('Shopkeeper is not authorized to make transactions', 401);
        }

        $payee = $this->retrievePayee($data);

        if (!$payee) {
            throw new InvalidDataProviderException('User Not Found', 404);
        }

        $myWallet = Auth::guard($data['provider'])->user()->wallet;

        if (!$this->checkUserBalance($myWallet, $data['value'])) {
            throw new NotEnoughMoneyException('You dont have this value to transfer.', 422);
        }

        if (!$this->isServiceAbleToMakeTransaction()) {
            throw new IdleServiceException('Service is not responding. Try again later.');
        }

        return $this->makeTransaction($payee, $data);
    }

    public function guardCanTransfer(): bool
    {
        if (Auth::guard('users')->check()) {
            return true;
        }
        
        if (Auth::guard('shopkeepers')->check()) {
            return false;
        }
        
        throw new InvalidDataProviderException('Provider Not found', 422);
    }

    public function getProvider(string $provider)
    {
        if ($provider == "users") {
            return new User();
        }
        
        if ($provider == "shopkeepers") {
            return new Shopkeeper();
        }
        
        throw new InvalidDataProviderException('Provider Not found', 422);
    }

    private function checkUserBalance(Wallet $wallet, $money)
    {
        return $wallet->balance >= $money;
    }


    /**
     * @param array $data
     */
    private function retrievePayee(array $data)
    {
        try {
            $model = $this->getProvider($data['provider']);
            return $model->find($data['payee']);
        } catch (InvalidDataProviderException | \Exception $e) {
            return false;
        }

    }

    private function makeTransaction($payee, array $data)
    {
        $payload = [
            'id' => Uuid::uuid4()->toString(),
            'payer_wallet_id' => Auth::guard($data['provider'])->user()->wallet->id,
            'payee_wallet_id' => $payee->wallet->id,
            'value' => $data['value']
        ];

        return $this->dbManager->transaction(function () use ($payload) {
            $transaction = $this->transactionModel->create($payload);

            $transaction->walletPayer->withdraw($payload['value']);
            $transaction->walletPayee->deposit($payload['value']);

            event(new SendNotification($transaction));

            return $transaction;
        });
    }

    private function isServiceAbleToMakeTransaction(): bool
    {
        $service = app(MockyService::class)->authorizeTransaction();
        return $service['message'] == 'Autorizado';
    }
}
