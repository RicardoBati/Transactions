<?php


namespace App\Repositories;


use App\Models\Shopkeeper;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\InvalidDataProviderException;

class AuthRepository
{
    protected $hasher;

    public function __construct(HasherContract $hasher)
    {
        $this->hasher = $hasher;
    }

    public function authenticate(string $provider, array $fields): array
    {

        $selectedProvider = $this->getProvider($provider);
        $model = $selectedProvider->where('email', $fields['email'])->first();
        if (!$model) {
            throw new AuthorizationException('Wrong credentials', 401);
        }

        if (!$this->hasher->check($fields['password'], $model->password)) {
            throw new AuthorizationException('Wrong credentials', 401);
        }

        $token = $model->createToken($provider);

        return [
            'access_token' => $token->accessToken,
            'expires_at' => $token->token->expires_at,
            'provider' => $provider
        ];
    }

    public function getProvider(string $provider)
    {
        if ($provider == "user") {
            return new User();
        }
    
        if ($provider == "shopkeeper") {
            return new Shopkeeper();
        }
    
        throw new InvalidDataProviderException('Provider Not found');
    }
}
