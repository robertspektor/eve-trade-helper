<?php

namespace App\Http\Controllers;

use App\Exceptions\AuthException;
use App\Services\Auth\AuthService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService)
    {
    }

    public function login(): Redirector|Application|RedirectResponse
    {
        return redirect($this->authService->getOAuthLoginUrl());
    }

    /**
     * @throws AuthException
     */
    public function callback(Request $request): Response
    {
        $this->authService->authorizeByCode($request);

        return response()->json(['status' => 'ok']);
    }
}
