<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\RegistrationRequest;

class AuthController extends Controller
{
    //

    public function index() {
        return view('auth.login');
    }


    public function login(LoginRequest $request) {
        $validateData = $request->validated();

        $user = DB::table('users')->where('email', $validateData['email'])->first();

        if ($user) {
            if (Hash::check($validateData['password'], $user->password)) {

                Auth::loginUsingId($user->id);
                notify()->success($user->username . ' welcome to Barta!');
                return redirect()->route('dashboard');
            }
        } else {
            notify()->error('Something went wrong. Please check your username or password!');
            return redirect()->route('login');
        }
    }

    public function register() {
        return view('auth.register');
    }

    public function store(RegistrationRequest $request) {
        $validateData = $request->validated();

        $uuid = Str::uuid();

        $username = DB::table('users')->where('username', $validateData['username'])->first();
        if ($username) {
            notify()->error('This username is already taken!');
            return redirect()->route('register')->withInput($validateData);
        }

        $data = DB::table('users')->insert([
            'uuid' => $uuid,
            'first_name' => $validateData['first_name'],
            'last_name' => $validateData['last_name'],
            'username' => $validateData['username'],
            'email' => $validateData['email'],
            'password' => Hash::make($validateData['password']),
            'created_by' => $validateData['email'],
        ]);

        if ($data) {
            notify()->success('Registration Successfully Completed!');
            return redirect()->route('register');
        }else{
            notify()->error('Something went wrong please try again later!');
            return redirect()->route('register');
        }
    }

    public function logout() {
        Session::flush();
        Auth::logout();

        notify()->info('Logged out!');
        return Redirect()->route('login');
    }
}
