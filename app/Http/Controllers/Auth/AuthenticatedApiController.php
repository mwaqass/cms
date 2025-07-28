<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthenticatedApiController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
                'device_name' => 'required',
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => trans('auth.failed'),
                ]);
            }

            $token = $user->createToken($request->device_name)->plainTextToken;

            $userData = $user->only([
                'name',
                'email',
            ]);

            $userData['token'] = $token;
            return $userData;
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        $request
            ->user()
            ->currentAccessToken()
            ->delete();
        return [
            'code' => 200,
            'message' => trans('auth.loggedout'),
        ];
    }

    /**
     * Show dashboard for each user.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile(Request $request)
    {
        $user = $request->user()->only([
            'name',
            'email',
        ]);
        $user['token'] = trim($request->header('Authorization'), 'Bearer ');
        return compact('user');
    }
}
