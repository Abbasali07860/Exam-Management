@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 p-10 bg-gray-100 rounded-xl shadow-2xl max-w-7xl">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Teacher Dashboard</h2>

    <div class="mb-6">
        <a href="{{ route('teacher.profile') }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm shadow">
            View/Edit Profile
        </a>
    </div>

    <h3 class="text-xl font-semibold text-gray-700 mb-4">Students</h3>

    <div class="overflow-x-auto rounded-xl bg-white shadow-2xl">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-indigo-600 text-white uppercase text-xs">
                <tr>
                    <th class="px-6 py-4 text-left">Image</th>
                    <th class="px-6 py-4 text-left">Name</th>
                    <th class="px-6 py-4 text-left">Email</th>
                    <th class="px-6 py-4 text-left">Mobile</th>
                    <th class="px-6 py-4 text-left">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @if($students->isEmpty())
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center py-8">
                            <svg class="w-12 h-12 mb-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-lg font-medium">No Students Found</span>
                        </div>
                    </td>
                </tr>
                @else
                @foreach($students as $student)
                <tr class="hover:bg-indigo-50 transition duration-200 ease-in-out transform hover:scale-[1.01]">
                    <td class="px-6 py-3">
                        <img src="{{ $student->image ? asset('storage/profile_images/' . $student->image) : asset('default/user.png') }}"
                             alt="Profile Image" class="w-12 h-12 rounded-full object-cover border-2 border-indigo-200">
                    </td>
                    <td class="px-6 py-3 font-medium text-gray-900">{{ $student->name }}</td>
                    <td class="px-6 py-3 text-gray-700">{{ $student->email }}</td>
                    <td class="px-6 py-3 text-gray-700">{{ $student->mobile }}</td>
                    <td class="px-6 py-3">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                            {{ $student->status === 'active' ? 'bg-green-300 text-green-900' : 'bg-red-300 text-red-900' }}">
                            {{ ucfirst($student->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>

    @if(!$students->isEmpty())
    <div class="mt-6 flex justify-center">
        {{ $students->links() }}
    </div>
    @endif
</div>
@endsection