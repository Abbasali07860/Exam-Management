<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\admin\Controller;
use App\Models\User;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class AdminController extends Controller
{
    private const PROFILE_IMAGE_PATH = 'profile_images';

    public function __construct()
    {
        // $this->middleware('role:admin');
    }

    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('mobile', 'like', "%$search%");
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by role
        if ($request->has('role')) {
            $query->where('role', $request->input('role'));
        }

        $users = $query->paginate(5);

        return view('admin.users.list', ['users' => $users]);
    }

    public function create()
    {
        return view('admin.users.createuser');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'mobile' => 'nullable|string|max:15',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,teacher,student',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->mobile = $data['mobile'] ?? null;
        $user->password = Hash::make($data['password']);
        $user->role = $data['role'];
        $user->status = 'inactive';

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->file('image')->extension();
            $request->file('image')->storeAs(self::PROFILE_IMAGE_PATH, $imageName, 'public');
            $user->image = $imageName;
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edituser', ['user' => $user]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'mobile' => 'nullable|string|max:15',
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,teacher,student',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->mobile = $data['mobile'] ?? null;
        $user->role = $data['role'];

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

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->image && Storage::disk('public')->exists(self::PROFILE_IMAGE_PATH . '/' . $user->image)) {
            Storage::disk('public')->delete(self::PROFILE_IMAGE_PATH . '/' . $user->image);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function bulkUpdateStatus(Request $request)
    {
        $userIds = json_decode($request->input('user_ids'), true);

        $data = $request->validate([
            'status' => 'required|in:active,inactive',
        ]);

        if (empty($userIds) || !is_array($userIds)) {
            return redirect()->back()->with('error', 'Please select users to update.');
        }

        User::whereIn('id', $userIds)->update(['status' => $data['status']]);

        return redirect()->back()->with('success', 'Status updated successfully.');
    }
}