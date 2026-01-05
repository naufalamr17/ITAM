<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception;

class AuthController extends Controller
{
    public function redirectToAzure()
    {
        return Socialite::driver('azure')->redirect();
    }

    public function handleAzureCallback()
    {
        try {
            $azureUser = Socialite::driver('azure')->user();
       
            $user = User::where('email', $azureUser->getEmail())->first();
       
            if($user){
                Auth::login($user);
                return redirect()->intended('dashboard');
            }else{
                return redirect()->route('login')->with('error', 'User not found in the system. Please contact the administrator.');
            }
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Azure Login failed: ' . $e->getMessage());
        }
    }
}
