<?php

namespace App\Providers;

use App\Facades\CustomHash;
use App\Models\AccessToken;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AccessTokenUserProvider extends ServiceProvider implements UserProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Auth::provider('access_token_user_provider', function($app, array $config) {
            // Return an instance of Illuminate\Contracts\Auth\UserProvider
            return new self($app);
        });
    }

    public function retrieveById($identifier)
    {
        return User::find($identifier);
    }

    public function retrieveByToken($identifier, $token)
    {
        return AccessToken::findByKey($token)?->user;
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
    }

    public function retrieveByCredentials(array $credentials)
    {
        if (!in_array('username', $credentials)) return null;
        if (!in_array('password', $credentials)) return null;

        $username = $credentials['username'];
        $password = $credentials['password'];
        if (!is_string($username) || !is_string($password)) return null;

        $user = User::findByUsername($credentials['username']);
        if (!isset($user)) return null;

        if (!$this->validateCredentials($user, $credentials)) {
            return null;
        }

        return $user;
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        if (!isset($user)) return false;
        if (!in_array('username', $credentials) || !in_array('password', $credentials)) return false;

        $username = $credentials['username'];
        $password = $credentials['password'];
        if (!is_string($username) || !is_string($password)) return false;

        return $username == $user->username && CustomHash::check($password, $user->encrypted_password);
    }

    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false)
    {
    }
}
