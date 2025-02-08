<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use InvalidArgumentException;

class AuthController extends Controller
{
    public function login(Request $request){

        $providerURL = 'http://login.docker.processton.com';

        $clientId = '9dc94d8f-c8a6-4e0e-965c-a5f034972226';

        $clientSecret = '$2y$10$WCFjfccJoAQggNzU9Qt3L.AGdwLCSs6Y8Q2H6GwSPztYU1REaD1Qm';

        $redirect = 'http://deployer.docker.processton.com/login';

        if($code = $request->code){

            $state = $request->session()->pull('state');

            $code = $request->code;

            if (!$code) {
                abort(403, $request->error);
            }

            throw_unless(
                strlen($state) > 0 && $state === $request->state,
                InvalidArgumentException::class,
                'Invalid state value.'
            );

            $response = Http::asForm()->withHeader('Content-Type', 'application/json')->post($providerURL.'/oauth/token', [
                'grant_type' => 'authorization_code',
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'redirect_uri' => $redirect,
                'code' => $code,
            ]);

            dd($response->json());
            if($response->status() != 200){
                abort(403, 'Invalid credentials');
            }

            $token = $response->json();


            $userRequest = Http::withToken($token['access_token'], $token['token_type'])->acceptJson()->get($providerURL.'/api/user');

            if($userRequest->status() != 200){
                abort(403, 'Something went wrong');
            }

            $user = $userRequest->json();

            dd($user);

        }

        $request->session()->put('state', $state = Str::random(40));

        $query = http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => $redirect,
            'response_type' => 'code',
            'scope' => '*',
            'state' => $state,
            'prompt' => '', // "none", "consent", or "login"
        ]);

        return redirect()->away($providerURL . '/oauth/authorize?' . $query);
    }
}
