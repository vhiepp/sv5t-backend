<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
     /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => ['login', 'getGoogleUrl', 'googleLogin']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
        $token = auth('api')->attempt($credentials);
        if (!$token || auth()->user()['role'] != 'admin') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token, auth()->user());
    }

    public function loginWithProvider(Request $request) {
        try {

            $provider = $request->input('providerData')[0]['providerId'];
            $providerId = $request->input('providerData')[0]['uid'];

            $user = User::where('provider', $provider)
                            ->where('provider_id', $providerId)
                            ->get();
            if (count($user) == 0) {
                $email = explode("@", $request->input('providerData')[0]['email']);
                $mssv = '';
                if ($email[1] == 'st.tvu.edu.vn') {
                    $mssv = $email[0];
                }
                $avatar = $request->input('providerData')[0]['photoURL']
                        ? $request->input('providerData')[0]['photoURL']
                        : env('APP_URL') . '/assets/images/avatars/avatar_' . rand(1, 24) . '.jpg';
                $name = explode(" ", $request->input('providerData')[0]['displayName'], 2);
                User::create([
                    'fullname' => $request->input('providerData')[0]['displayName'],
                    'sur_name' => !empty($name[0]) ? $name[0] : '',
                    'given_name' => !empty($name[1]) ? $name[1] : '',
                    'phone' => $request->input('providerData')[0]['phoneNumber'],
                    'email' => $request->input('providerData')[0]['email'],
                    'stu_code' => $mssv,
                    'provider' => $provider,
                    'provider_id' => $providerId,
                    'avatar' => $avatar,
                    'role' => 'user',
                    'password' => rand(),
                ]);

                return response()->json(['error' => 'Unauthorized'], 401);
            }
            $user = $user[0];

            if ($user['role'] == 'admin') {
                $token = auth()->tokenById($user['id']);
                if (!$token) {
                    return response()->json(['error' => 'Unauthorized'], 401);
                }

                return $this->respondWithToken($token, $user);
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function getGoogleUrl()
    {
        return response([
            'url' => Socialite::driver('google')->stateless()->redirect()->getTargetUrl()
        ]);
    }

    public function googleLogin()
    {
        try {
            //code...
            $userProvider = Socialite::driver('google')->stateless()->user()->user;

            $provider = 'google';
            $user = User::where('provider', $provider)->where('provider_id', $userProvider['id'])->where('role', 'admin')->first();

            if (! $token = auth()->tokenById($user['id'])) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            return $this->respondWithToken($token);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json([
            'user' => auth()->user(),
        ]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        $cookie = cookie()->forget('token');

        return response()->json(['message' => 'Successfully logged out'], 200)->cookie($cookie);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $user = null)
    {
        if ($user == null) {
            $user == auth()->user();
        }
        $cookie = cookie('token', $token, 60);
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => $user
        ])->cookie($cookie);
    }
}