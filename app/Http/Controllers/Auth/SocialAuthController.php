<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SocialAuthController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Redirect the user to the provider authentication page.
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        if (!config("services.{$provider}.client_id")) {
            abort(404, "Please configure the [{$provider}] parameters.");
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from provider.
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();

        $data = [
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'password' => bcrypt(Str::random(8)),
        ];

        $existingUser = User::whereEmail($data['email'])->first();

        if (!is_null($existingUser)) {
            Auth::login($existingUser);
        } else {
            $newUser = User::create($data);
            Auth::login($newUser);
        }

        return redirect()->intended('/admin');
    }
}
