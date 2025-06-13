<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'mobile' => ['required', 'numeric'],
            'role' => ['required', 'in:student,teacher'],
        ]);

        if ($validator->fails()) {
            // Convert validation errors into a single error message
            $errors = $validator->errors()->all();
            $errorMessage = implode(' ', $errors);
            return back()->with('error', $errorMessage ?: 'Please fill in all required fields.')->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'mobile' => $request->mobile,
            'role' => $request->role,
        ]);

        return redirect()->route('login')->with('success', 'Registration successful. Please login.');
    }
}