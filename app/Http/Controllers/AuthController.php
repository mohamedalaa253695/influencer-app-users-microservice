<?php
namespace App\Http\Controllers;

use Auth;
// use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Cookie;
use App\Http\Requests\UpdateInfoRequest;
use App\Http\Requests\UpdatePasswordRequest;
use Symfony\Component\HttpFoundation\Response ;

class AuthController
{
    public function login(Request $request)
    {
        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $scope = $request->input('scope');
            if ($user->isInfluencer() && $scope !== 'influencer') {
                return response([
                    'error' => 'Access denied!',
                ], Response::HTTP_FORBIDDEN);
            }

            $token = $user->createToken($scope, [$scope])->accessToken;

            $cookie = cookie('jwt', $token, 3600);

            return response([
                'token' => $token,
            ])->withCookie($cookie);
        }

        return response([
            'error' => 'Invalid Credentials!',
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function logout()
    {
        $cookie = Cookie::forget('jwt');

        return response([
            'message' => 'success'
        ])->withCookie($cookie);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create(
            $request->only('first_name', 'last_name', 'email')
            + [
                'password' => Hash::make($request->input('password')),
                'is_influencer' => 1,
            ]
        );

        return response($user, Response::HTTP_CREATED);
    }

    public function user()
    {
        // dd('here');

        return Auth::user();

        // $resource = new UserResource($user);

        // if ($user->isInfluencer()) {
        //     return $resource;
        // }

        // return $resource->additional([
        //     'data' => [
        //         'permissions' => $user->permissions(),
        //     ],
        // ]);
    }

    public function updateInfo(UpdateInfoRequest $request)
    {
        $user = Auth::user();

        $user->update($request->only('first_name', 'last_name', 'email'));

        return response($user, Response::HTTP_ACCEPTED);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        dd($request->all());
        $user = \Auth::user();

        $user->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return response($user, Response::HTTP_ACCEPTED);
    }

    public function authenticated()
    {
        return 1;
    }
}
