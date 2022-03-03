<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Exceptions\AuthException;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * @property string $access_token
 * @property string $token_type
 * @property int $expires_in
 * @property string $refresh_token
 * @property Carbon $expiry
 */
class AuthToken
{
    /**
     * @throws AuthException
     */
    public function __construct(Collection $data)
    {
        try {
            $validated = $this->validate($data->toArray());
            $this->access_token = $validated['access_token'];
            $this->token_type = $validated['token_type'];
            $this->expires_in = $validated['expires_in'];
            $this->refresh_token = $validated['refresh_token'];
            $this->expiry = Carbon::make($validated['expiry'])->timezone(Config::get('app.timezone'));
        } catch (\Exception $e) {
            throw new AuthException($e->getMessage());
        }
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->access_token;
    }

    /**
     * @param string $access_token
     */
    public function setAccessToken(string $access_token): void
    {
        $this->access_token = $access_token;
    }

    /**
     * @return string
     */
    public function getTokenType(): string
    {
        return $this->token_type;
    }

    /**
     * @param string $token_type
     */
    public function setTokenType(string $token_type): void
    {
        $this->token_type = $token_type;
    }

    /**
     * @return int
     */
    public function getExpiresIn(): int
    {
        return $this->expires_in;
    }

    /**
     * @param int $expires_in
     */
    public function setExpiresIn(int $expires_in): void
    {
        $this->expires_in = $expires_in;
    }

    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refresh_token;
    }

    /**
     * @param string $refresh_token
     */
    public function setRefreshToken(string $refresh_token): void
    {
        $this->refresh_token = $refresh_token;
    }

    /**
     * @return Carbon
     */
    public function getExpiry(): Carbon
    {
        return $this->expiry;
    }

    /**
     * @param Carbon $expiry
     */
    public function setExpiry(Carbon $expiry): void
    {
        $this->expiry = $expiry;
    }

    /**
     * @throws ValidationException
     */
    private function validate($data): array
    {
        $validator = Validator::make($data, [
            'access_token' => 'required|max:255',
            'token_type' => 'required|in:Bearer',
            'expires_in' => 'required|int',
            'refresh_token' => 'required',
            'expiry' => 'required|date'
        ]);
        $validator->validate();
        return $validator->validated();
    }

    public function collect(): Collection
    {
        return collect([
            'access_token' => $this->access_token,
            'token_type' => $this->token_type,
            'expires_in' => $this->expires_in,
            'refresh_token' => $this->refresh_token,
            'expiry' => $this->expiry
        ]);
    }

    public function isExpired(): bool
    {
        return $this->expiry <= Carbon::now();
    }
}
