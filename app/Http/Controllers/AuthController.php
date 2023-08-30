<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\ForgotPasswordMail;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Mail;
use function Laravel\Prompts\password;

class AuthController extends Controller
{
    //
    public function login(){

        // dd(Hash::make(12345));
        if(!empty(Auth::check()))
        {
            if(Auth::user()->user_type == 1)
            {
                return redirect('admin/dashboard');
            }
            elseif(Auth::user()->user_type == 2) {
                return redirect('teacher/dashboard');
            }
            elseif(Auth::user()->user_type == 3) {
                return redirect('student/dashboard');
            }
            elseif(Auth::user()->user_type == 4) {
                return redirect('parent/dashboard');
            }
        }

        return view('auth.login');
    }

    public function Authlogin(Request $request)
    {
        $remember = !empty($request->remember) ? true : false;
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember))
        {
            if(Auth::user()->user_type == 1)
            {
                return redirect('admin/dashboard');
            }
            elseif(Auth::user()->user_type == 2) {
                return redirect('teacher/dashboard');
            }
            elseif(Auth::user()->user_type == 3) {
                return redirect('student/dashboard');
            }
            elseif(Auth::user()->user_type == 4) {
                return redirect('parent/dashboard');
            }
            
        }
        else
        {
            return redirect()->back()->with('error', 'Please enter correct email and password');
        }
    }

    public function forgotpassword(){
        return view('auth.forgot');
    }

    public function logout(){
        Auth::logout();
        return redirect(url(''));
    }

    public function Postforgotpassword(Request $request){
        $user = User::getEmailsingle($request-> email);
        if(!empty($user))
        {
            $user->remember_token = Str::random(30);
            Mail::to($user->email)->send(new ForgotPasswordMail($user));
            return redirect()->back()->with('success', "Please check your email and reset password.");
        }
        else
        {
            return redirect()->back()->with('error', "Email not found in the system");
        }
    }
}
