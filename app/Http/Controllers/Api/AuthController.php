<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use App\Permissions\V1\Abilities;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Api\LoginUserRequest;

class AuthController extends Controller
{
    use ApiResponses;

    /**
     * Login
     *
     * Authenticates the user and returns the user's API token.
     *
     * @unauthenticated
     * @group Authentication
     * @response 200
        {
            "data": {
                "token": "{YOUR_AUTH_KEY}"
            },
            "message": "Authenticated",
            "status": 200
        }
     */
    public function login(LoginUserRequest $request)
    {
        $request->validated($request->all());

        if(!Auth::attempt($request->only('email', 'password'))){
            return $this->error('Invalidated credentials', 401);
        }

        $user = User::firstWhere('email',$request->email);

        return $this->ok(
            'Authenticated',
            [
                'token' => $user->createToken(
                    'API token for ' . $user->email,
                    Abilities::getAbilities($user),
                    now()->addMonth())->plainTextToken
            ]
        );
    }

    public function register()
    {
        return $this->ok('Register','data');
    }

    /**
     * Logout
     *
     * Signs out the user and destroy's the API token.
     *
     * @group Authentication
     * @response 200
        {
        }
     */
    public function logout(Request $request)
    {
        // return $request->user()->tokens()->where('id',$tokenId)->delete();
        $request->user()->currentAccessToken()->delete();
        return $this->ok('');
    }
}
