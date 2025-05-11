<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordChanged;

class PasswordController extends Controller
{
    public function showChangeForm()
    {
        return view('auth.change-password');
    }

    public function update(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match.']);
        }
        $user->password = Hash::make($request->new_password);
        $user->save();
        // Send new password via email
        // Mail::to($user->email)->send(new PasswordChanged($user->name, $request->new_password));

        $existingActivity = Activity::where('user_id', $user->id)
            ->where('activity', 'Change Password')
            ->first();

        if ($existingActivity) {
            $existingActivity->touch(); // updates 'updated_at'
        } else {
            Activity::create([
                'user_id' => $user->id,
                'activity' => 'Change Password',
            ]);
        }

        return back()->with('success', 'Password updated successfully!');
    }
}

