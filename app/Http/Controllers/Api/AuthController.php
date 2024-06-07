<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginUserRequest;
use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponses;

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
                    ['*'],
                    now()->addMonth())->plainTextToken
            ]
        );
    }

    public function register()
    {
        return $this->ok('Register','data');
    }

    public function logout(Request $request)
    {
        // return $request->user()->tokens()->where('id',$tokenId)->delete();
        $request->user()->currentAccessToken()->delete();
        return $this->ok('');
    }
}
