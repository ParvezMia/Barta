<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UpdateProfileRequest;

class DashboardController extends Controller
{


    public function dashboard() {
        $auth = auth()->user();
        return view('index');
    }

    public function profile() {
        $user = auth()->user();

        if ($user) {
            return view('pages.profile', compact('user'));
        }else{
            notify()->error('Something went wrong Please try again later');
            return redirect()->route('dashboard');
        }
    }

    public function edit() {
        $user = auth()->user();
        if ($user) {
            return view('pages.edit-profile', compact('user'));
        }else{
            notify()->error('Something went wrong Please try again later');
            return redirect()->route('dashboard');
        }
    }

    public function update(UpdateProfileRequest $request) {
        $validateData = $request->validated();

        $user = auth()->user();
        if ($validateData['password']) {
            $data = DB::table('users')->update([
                'first_name' => $validateData['first_name'],
                'last_name' => $validateData['last_name'],
                'password' => Hash::make($validateData['password']),
                'bio' => $validateData['bio'],
                'updated_by' => $user->email,
            ]);
        }else{
            $data = DB::table('users')->update([
                'first_name' => $validateData['first_name'],
                'last_name' => $validateData['last_name'],
                'updated_by' => $user->email,
                'bio' => $validateData['bio'],
            ]);
        }

        if ($data) {
            notify()->success('Profile updated successfully!');
            return redirect()->route('profile.update');
        } else {
            notify()->success('Something went wrong! Could not updated the profile');
            return redirect()->route('profile.update');
        }
    }

}
