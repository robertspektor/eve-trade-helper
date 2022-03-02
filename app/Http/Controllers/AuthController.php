<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Redirect the user to the Eve Online authentication page.
     *
     * @return \Illuminate\Http\RedirectResponse|RedirectResponse
     */
    public function login(): RedirectResponse|\Illuminate\Http\RedirectResponse
    {
        return Socialite::driver('eveonline')
            ->setScopes([
                'esi-wallet.read_character_wallet.v1',
                'esi-universe.read_structures.v1',
                'esi-assets.read_assets.v1',
            ])
            ->redirect();
    }

    /**
     * Obtain the user information from Eve Online.
     *
     * @return Response
     */
    public function callback(): Response
    {
        $user = Socialite::driver('eveonline')->user();

        dd($user);
    }
}
