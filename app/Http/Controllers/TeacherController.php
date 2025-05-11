<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    private const PROFILE_IMAGE_PATH = 'profile_images';

    public function __construct()
    {
        $this->middleware('role:teacher');
    }

    public function dashboard()
    {
        $students = User::where('role', 'student')->paginate(5);
        return view('teacher.dashboard', ['students' => $students]);
    }

    public function profile()
    {
        return view('teacher.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'mobile' => 'nullable|string|max:15',
            'password' => 'nullable|string|min:8',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->mobile = $data['mobile'] ?? null;

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        if ($request->hasFile('image')) {
            if ($user->image && Storage::disk('public')->exists(self::PROFILE_IMAGE_PATH . '/' . $user->image)) {
                Storage::disk('public')->delete(self::PROFILE_IMAGE_PATH . '/' . $user->image);
            }
            $imageName = time() . '.' . $request->file('image')->extension();
            $request->file('image')->storeAs(self::PROFILE_IMAGE_PATH, $imageName, 'public');
            $user->image = $imageName;
        }

        $user->save();

        return redirect()->route('teacher.profile')->with('success', 'Profile updated successfully.');
    }
}