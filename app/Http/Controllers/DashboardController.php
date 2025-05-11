<?php

namespace App\Http\Controllers;

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
        $user = Auth::user();
        
        $data = [
            'user' => $user,
            'totalUsers' => User::count(),
            'activeUsers' => User::where('status', 'active')->count(),
            'inactiveUsers' => User::where('status', 'inactive')->count(),
            'totalExams' => Exam::count(),
            'totalStudents' => User::where('role', 'student')->count(),
            // 'publishedResults' => ExamResult::where('published', true)->count(),
            'recentActivities' => Activity::where('user_id', $user->id)
                ->latest()
                ->limit(10)
                ->get(),
            'upcomingExams' => Exam::where('start_date', '>=', now())
                ->orderBy('start_date')
                ->limit(5)
                ->get(),
        ];

        return view('dashboard', $data);
    }

    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
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

        // Update or create activity
        Activity::updateOrCreate(
            [
                'user_id' => $user->id,
                'activity' => 'Updated profile',
            ],
            [
                'updated_at' => now(),
            ]
        );

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully');
    }
}