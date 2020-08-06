<?php

namespace App\Http\Controllers\API;

use Auth;
use Socialite;
use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\ResponseFormatter;

class AuthenticationController extends Controller
{
    /**
     * Login API
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        $user = User::whereEmail(request('email'))->first();

        if (!is_null($user)){
            if (\Hash::check(request()->password, $user->password)) {
                $data['token'] = $user->createToken(config('app.name'))->accessToken;
    
                return ResponseFormatter::success($data);
            }
        }
            
        return ResponseFormatter::error(401, __('auth.failed'));
    }

    /**
     * Register API
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required',
            'c_password'    => 'required|same:password',
            'phone_number'  => 'numeric'
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(400, $validator->errors()->first());
        }

        $payload = $request->all();
        $payload['password'] = bcrypt($payload['password']);
        $user = User::create($payload);

        $data['token'] = $user->createToken(config('app.name'))->accessToken;

        return ResponseFormatter::success($data);
    }

    /**
     * Authenticate from Social Media.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function authenticateSocialMedia(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'provider' => 'required|in:facebook,google',
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(400, $validator->errors()->first());
        }

        $token = $request->token;

        if ($request->provider == 'google') {
            $client = new \Google_Client([
                'client_id' => config('services.google.client_id'),
                'client_secret' => config('services.google.client_secret'),
            ]);
            $payload = $client->verifyIdToken($token);
            if ($payload) {
                $result['id'] = $payload['sub'];
                $result['email'] = $payload['email'];
                $result['name'] = $payload['name'];
                $result['avatar'] = $payload['picture'];
            } else {
                \Log::error($token . ' => Invalid Token (Google) ');
                $result = null;

                return ResponseFormatter::error(400, 'Ada masalah saat autentikasi, silahkan coba lagi!');
            }
            $payload = (object) $result;
        } else {
            $payload = Socialite::driver($request->provider)->userFromToken($token);
        }

        $user = $this->findOrCreateUser($payload, $request->provider);

        $data['token'] = $user->createToken(config('app.name'))->accessToken;

        return ResponseFormatter::success($data);
    }

    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     * @param  $user Socialite user object
     * @param $provider Social auth provider
     * @return  User
     */
    public function findOrCreateUser($user, $provider)
    {
        $authUser = User::where('email', $user->email)->orWhere(function($query) use($provider, $user) {
            $query->where('provider_id', $user->id)->where('provider', $provider);
        })->first();

        if ($authUser) {
            if (!is_null($user->email)) {
                $authUser->email = $user->email;
            }

            return $authUser;
        }

        $contents = file_get_contents($user->avatar);
        $filename = 'assets/' . 'users/' . $user->id . md5(time()) . '.jpg';
        \Storage::disk('public')->put($filename, $contents);

        $user = User::create([
            'name'     => $user->name,
            'email'    => $user->email,
            'provider' => $provider,
            'provider_id' => $user->id,
            'photo'    => $filename,
        ]);

        return $user;
    }
}
