<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{

    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => ['signin', 'microsoftGetUrl']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function signin()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            $emailStatus = User::where('email', $credentials['email'])->count() > 0 ? 1 : 4;
            $passwordStatus = $emailStatus == 1 ? 4 : 1;

            return response()->json([
                'is_valid' => false,
                'status_signin' => [
                    'email' => $emailStatus,
                    'password' => $passwordStatus
                ],
                'user' => null
            ]);
        }

        
        $cookie = cookie('token', $token, 60);
        return response()->json([
            'is_valid' => true,
            'status_signin' => [
                'email' => 1,
                'password' => 1
            ],
            'user' => auth()->user()
        ])->cookie($cookie);
    }

    public function microsoftGetUrl(Request $request) {
        try {
            //code...
            $microsoft = new \myPHPnotes\Microsoft\Auth (
                env('MICROSOFT_TENANT'),
                env('MICROSOFT_CLIENT_ID'),
                env('MICROSOFT_SECRET_ID'),
                $request->input('domain') . env('MICROSOFT_CALLBACK_URL'),
                ["User.Read"]
            );
        
            return response(['url' => $microsoft->getAuthUrl()]);
        } catch (\Throwable $th) {
            //throw $th;
            return response(['url' => null]);
        }
    }

    public function microsoftSignin(Request $request) {
        try {
            //code...
            $auth = new \myPHPnotes\Microsoft\Auth(
                \myPHPnotes\Microsoft\Handlers\Session::get("tenant_id"),
                \myPHPnotes\Microsoft\Handlers\Session::get("client_id"),
                \myPHPnotes\Microsoft\Handlers\Session::get("client_secret"),
                \myPHPnotes\Microsoft\Handlers\Session::get("redirect_uri"),
                \myPHPnotes\Microsoft\Handlers\Session::get("scopes")
            );
    
            $tokens = $auth->getToken($request->input('code'), $request->input('state'));
            $accessToken = $tokens->access_token;
            $auth->setAccessToken($accessToken);
    
            $userProvider = new \myPHPnotes\Microsoft\Models\User;
    
            $email = explode("@", $userProvider->data->getUserPrincipalName());
    
            // if ($email[1] != 'st.tvu.edu.vn') {
            //     return redirect()->route('client.login')
            //                      ->with('status', 'Chỉ sinh viên Trường đại học Trà Vinh mới được đăng nhập với Microsoft.');
            // }
    
            $mssv = '';
            if ($email[1] == 'st.tvu.edu.vn') {
                $mssv = $email[0];
            }
    
    
            $provider = 'microsoft';
    
            $user = User::where('provider', $provider)->where('provider_id', $userProvider->data->getId())->first();
    
            if (!$user) {
                $user = new User();
                $user->fullname = $userProvider->data->getDisplayName();
                $user->sur_name = $userProvider->data->getSurname();
                $user->given_name = $userProvider->data->getGivenName();
                $user->email = $userProvider->data->getUserPrincipalName();
                $user->password = \Hash::make(rand());
                $user->phone = $userProvider->data->getMobilePhone();
                $user->class = $userProvider->data->getJobTitle();
                $user->role = 0;
                $user->stu_code = $mssv;
                $user->avatar = env('APP_URL') . '/assets/images/avatars/avatar_' . rand(1, 24) . '.jpg';
                $user->provider = $provider;
                $user->provider_id = $userProvider->data->getId();
                $user->save();
            }
            $token = auth()->tokenById($user->id);
            $cookie = cookie('token', $token, 60);
            return response([
                'is_valid' => true,
                'user' => auth()->user()
            ])->cookie($cookie);
        } catch (\Throwable $th) {
            //throw $th;
            return \response($th, 500);
        }
        
    }

    public function user() {
        return response([
            'user' => auth()->user()
        ]);
    }

    public function signout() {
        auth()->logout();
        
        $cookie = cookie()->forget('token');

        return response()->json(['message' => 'Successfully logged out'], 200)->cookie($cookie);
    }
}