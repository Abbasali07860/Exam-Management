<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\admin\Controller;
use App\Models\User;
use App\Models\Activity;
use App\Models\Exam;
use App\Models\ExamResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    private const PROFILE_IMAGE_PATH = 'profile_images';

    public function index()
    {
        $data = [
            'totalExams' => Exam::count(),
            'totalUsers' => User::where('role', 'student')->count(),
            'upcomingExams' => Exam::where('start_date', '>=', now())
                ->orderBy('start_date')
                ->limit(5)
                ->get(),
        ];

        return view('admin.dashboard', $data);
    }

    public function edit()
    {
        return view('admin.profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif', 'max:2048'],
        ]);

        $user->fill([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($user->image && Storage::disk('public')->exists(self::PROFILE_IMAGE_PATH . '/' . $user->image)) {
                Storage::disk('public')->delete(self::PROFILE_IMAGE_PATH . '/' . $user->image);
            }

            $imageName = time() . '.' . $request->file('image')->extension();
            $request->file('image')->storeAs(self::PROFILE_IMAGE_PATH, $imageName, 'public');
            $user->image = $imageName;
        }
        $user->save();
        return redirect()->route('admin.profile.edit')->with('success', 'Profile updated successfully');
    }
}