<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm(Request $request)
    {
        $type = $request->route('type'); // 'admin' or 'user'
        if ($type === 'admin') {
            return view('auth.login'); 
        } else {
            return view('user.login'); 
        }
    }

    public function login(Request $request)
    {
        $type = $request->route('type'); // detect if admin or user

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            // Convert validation errors into a single error message
            $errors = $validator->errors()->all();
            $errorMessage = implode(' ', $errors); // Combine all error messages
            return back()->with('error', $errorMessage ?: 'Please fill in all required fields.')->withInput();
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            // Redirect based on role
            if ($type === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Admin logged in successfully.');
            } elseif ($type === 'user') {
                $role = Auth::user()->role; // make sure role field exists in your users table
                if ($role === 'teacher') {
                    return redirect()->route('teacher.dashboard')->with('success', 'Teacher logged in successfully.');
                } elseif ($role === 'student') {
                    return redirect()->route('student.dashboard')->with('success', 'Student logged in successfully.');
                }
                // Fallback for unexpected roles
                return redirect()->route('user.login')->with('error', 'Invalid user role.');
            }
        }

        return back()->with('error', 'Invalid credentials.')->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $routeName = $request->route()->getName();

        if ($routeName === 'admin.logout') {
            return redirect()->route('admin.login')->with('success', 'Logged out successfully.');
        }

        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}