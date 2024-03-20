<?php


namespace App\Http\Controllers\Transactions;


use App\Exceptions\NotEnoughMoneyException;
use App\Exceptions\IdleServiceException;
use App\Exceptions\TransactionDeniedException;
use App\Http\Controllers\Controller;
use App\Repositories\Transaction\TransactionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\InvalidDataProviderException;

class TransactionsController extends Controller
{
    /**
     * @var TransactionRepository
     */
    private $repository;

    /**
     * @var Log
     */
    protected $logger;

    public function __construct(TransactionRepository $repository, Log $logger)
    {
        $this->repository = $repository;
        $this->logger = $logger;
    }

    public function postTransaction(Request $request)
    {
        
        $this->validate($request, [
            'provider' => 'required|in:users,shopkeepers',
            'payee' => 'required',
            'value' => 'required|numeric',
        ]);

        $fields = $request->only(['provider', 'payee', 'value']);

        try {
            $result = $this->repository->handle($fields);
            return response()->json($result);
        } catch (InvalidDataProviderException | NotEnoughMoneyException $exception) {
            return response()->json(['errors' => ['main' => $exception->getMessage()]], $exception->getCode());
        } catch (TransactionDeniedException | IdleServiceException $exception) {
            return response()->json(['errors' => ['main' => $exception->getMessage()]], 401);
        } catch (\Exception $exception) {
            $this->logger->critical('[Transaction denied]', [
                'message' => $exception->getMessage()
            ]);
        }
    }
}
