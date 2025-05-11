@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 p-12 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl shadow-2xl max-w-7xl">
    <div class="flex justify-between items-center mb-8">
        <h2 class="text-3xl font-extrabold text-gray-900">Class Management</h2>
        <a href="{{ route('admin.classrooms.create') }}"
           class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-full text-base font-bold border border-gray-200 shadow-md hover:shadow-glow transition-all duration-300">
            Add New Class
        </a>
    </div>

    <!-- Classrooms Table -->
    <div class="overflow-x-auto rounded-2xl bg-white shadow-2xl border border-gray-200 animate-fade-in">
        <table class="min-w-full divide-y divide-gray-200 text-base">
            <thead class="bg-gradient-to-r from-blue-700 to-blue-600 text-white uppercase text-xs">
                <tr>
                    <th class="px-6 py-4 text-left">Name</th>
                    <th class="px-6 py-4 text-left">Assigned Subjects</th>
                    <th class="px-6 py-4 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @if($classrooms->isEmpty())
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center py-12">
                                <svg class="w-16 h-16 mb-4 text-gray-400 animate-pulse" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-xl font-semibold text-gray-600">No Classes Found</span>
                            </div>
                        </td>
                    </tr>
                @else
                    @foreach($classrooms as $classroom)
                        <tr class="even:bg-gray-50 hover:bg-blue-50 hover:shadow-md transition-all duration-300 transform hover:scale-[1.01]">
                            <td class="px-6 py-4 font-semibold text-gray-900">{{ $classroom->name }}</td>
                            <td class="px-6 py-4 text-gray-700">
                                {{ $classroom->subjects->pluck('name')->implode(', ') ?: 'None' }}
                            </td>
                            <td class="px-6 py-4 space-x-4">
                                <a href="{{ route('admin.classrooms.edit', $classroom->id) }}"
                                   class="bg-gradient-to-r from-gray-200 to-gray-300 text-gray-800 px-5 py-2 rounded-full font-semibold hover:from-gray-300 hover:to-gray-400 hover:shadow-glow transition-all duration-300">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.classrooms.destroy', $classroom->id) }}" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="bg-gradient-to-r from-red-500 to-red-600 text-white px-5 py-2 rounded-full font-semibold hover:from-red-600 hover:to-red-700 hover:shadow-glow transition-all duration-300"
                                            onclick="return confirm('Are you sure you want to delete this class?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    @if(!$classrooms->isEmpty())
        <div class="mt-8 flex justify-center">
            {{ $classrooms->links() }}
        </div>
    @endif
</div>
@endsection