<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activity;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            $this->logActivity('Logged in');
            return redirect()->route('dashboard')->with('success', 'Logged in successfully.');
        }
        return back()->with('error', 'Invalid credentials.')->withInput();
    }

    public function logout()
    {
        if (Auth::check()) {
            $this->logActivity('Logged out');
            Auth::logout();
        }
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }

    private function logActivity(string $activity)
    {
        $existingActivity = Activity::where('user_id', Auth::id())              
            ->where('activity', $activity)
            ->first();

        if ($existingActivity) {
            $existingActivity->touch(); // updates 'updated_at'
        } else {
            Activity::create([
                'user_id' => Auth::id(),                
                'activity' => $activity,
            ]);
        }
    }
}