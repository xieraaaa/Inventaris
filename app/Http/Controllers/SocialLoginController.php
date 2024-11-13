<?php

namespace App\Http\Controllers;

use App\Models\SocialLogin;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class SocialLoginController extends Controller
{
    public function toProvider($driver)
    {
        return Socialite::driver($driver)->redirect();
    }

    public function handleCallback($driver)
    {
        $user= Socialite::driver($driver)->user();

        $user_account=SocialLogin::where('provider', $driver)->where('provider_id', $user->getId())->first();

        if ($user_account) {
            Auth::login($user_account->user);
            
            Session::regenerate();

            return redirect()->intended('dashboard');
        }

        $db_user=User::where('email', $user->getEmail())->first();

        if ($db_user) {
            SocialLogin::create([
                'provider'=>$driver,
                'provider_id'=>$user->getId(),
                'user_id'=>$db_user->id
            ]);
        }else{
            $db_user=User::create([
                'name'=>$user->getName(),
                'email'=>$user->getEmail(),
                'password'=>bcrypt(rand(1000,9999)) 
            ]);
            SocialLogin::create([
                'provider'=>$driver,
                'provider_id'=>$user->getId(),
                'user_id'=>$db_user->id
            ]);
        }

        Auth::login($db_user);
            
        Session::regenerate();

        return redirect()->intended('dashboard');

    }
}
