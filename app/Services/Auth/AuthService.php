<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Exceptions\AuthException;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AuthService
{
    public function getOAuthLoginUrl(): string
    {
        $state = Str::random(40);
        Session::put('state', $state);

        return 'https://login.eveonline.com/oauth/authorize?' . http_build_query([
            'response_type' => 'code',
            'redirect_uri' => config('eve.redirect'),
            'client_id' => config('eve.client_id'),
            'client_secret' => config('eve.client_secret'),
            'scope' => implode(' ', [
                'esi-wallet.read_character_wallet.v1',
                'esi-universe.read_structures.v1',
                'esi-assets.read_assets.v1'
            ]),
            'state' => $state
        ]);
    }

    /**
     * @throws AuthException
     */
    public function authorizeByCode(Request $request)
    {
        try {
            $code = $request->get('code');
            $state = $request->get('state');

            if (Session::get('state') !== $state) {
                throw new Exception('invalid state');
            }

            $this->authByCode($code);

        } catch (Exception $e) {
            throw new AuthException($e->getMessage());
        }
    }

    /**
     * @throws AuthException
     */
    public function getToken(): AuthToken
    {
        $json = $this->readCredentials();
        $token = new AuthToken($json);

        if ($token->isExpired()) {

            $this->refreshToken($token);
            $json = $this->readCredentials();
        }

        return new AuthToken($json);
    }

    /**
     * @throws AuthException
     */
    private function refreshToken(AuthToken $authToken): void
    {
        $response = Http::withBasicAuth(
            config('eve.client_id'),
            config('eve.client_secret'),
        )->post('https://login.eveonline.com/oauth/token', [
            'refresh_token' => $authToken->getRefreshToken(),
            'grant_type' => 'refresh_token'
        ]);

        if ($response->status() !== 200) {
            throw new AuthException($response->body());
        }

        $this->writeCredentials($response->collect());
    }

    /**
     * @throws AuthException
     */
    private function authByCode(string $code): void
    {
        $response = Http::withBasicAuth(
            config('eve.client_id'),
            config('eve.client_secret'),
        )->post('https://login.eveonline.com/oauth/token', [
            'code' => $code,
            'grant_type' => 'authorization_code'
        ]);

        if ($response->status() !== 200) {
            throw new AuthException($response->body());
        }

        $this->writeCredentials($response->collect());
    }

    private static function writeCredentials(Collection $token): void
    {
        $token->put('expiry', Carbon::now()->addSeconds($token->get('expires_in')));
        Storage::put('credentials.json', $token->toJson());
    }

    private function readCredentials(): Collection
    {
        return collect(json_decode(Storage::get('credentials.json')));
    }
}

