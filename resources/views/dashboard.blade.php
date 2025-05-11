@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="main-content flex-1 min-h-screen bg-gradient-to-br from-gray-100 to-gray-200 transition-all duration-300">
    <div class="flex justify-center items-start min-h-[calc(100vh-70px)] pt-12 px-4 md:px-8 lg:px-12">
        <div class="max-w-7xl w-full flex flex-col gap-10">
            <!-- Main Dashboard Section -->
            <div class="w-full bg-white p-10 rounded-2xl shadow-md border border-gray-200 transition-all duration-300 hover:shadow-lg animate-fade-in">
                <div class="flex items-center gap-4 mb-8">
                    <i class="fas fa-tachometer-alt text-4xl text-blue-600"></i>
                    <h1 class="text-4xl font-extrabold text-gray-900">Dashboard Overview</h1>
                </div>
                <div class="mb-8">
                    <h2 class="text-2xl font-semibold text-gray-800">Welcome, {{ Auth::user()->name }}</h2>
                    <p class="text-gray-600 text-sm mt-2">{{ Auth::user()->email }}</p>
                </div>

                <!-- Stats Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">
                    <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-8 rounded-2xl text-white shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <div class="flex items-center gap-4">
                            <i class="fas fa-users text-3xl text-blue-200"></i>
                            <div>
                                <a href="{{ route('admin.users.index')}}">
                                <div class="text-sm font-medium">Total Users</div>
                                <div class="text-3xl font-bold">{{ $totalUsers }}</div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-green-600 to-green-700 p-8 rounded-2xl text-white shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <div class="flex items-center gap-4">
                            <i class="fas fa-user-check text-3xl text-green-200"></i>
                            <div>
                                <div class="text-sm font-medium">Active Users</div>
                                <div class="text-3xl font-bold">{{ $totalUsers }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-gray-600 to-gray-700 p-8 rounded-2xl text-white shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <div class="flex items-center gap-4">
                            <i class="fas fa-user-times text-3xl text-gray-200"></i>
                            <div>
                                <div class="text-sm font-medium">Inactive Users</div>
                                <div class="text-3xl font-bold">{{ $inactiveUsers }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 p-8 rounded-2xl text-white shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <div class="flex items-center gap-4">
                            <i class="fas fa-book text-3xl text-indigo-200"></i>
                            <div>
                                <a href="{{ route('admin.exams.index')}}">
                                <div class="text-sm font-medium">Total Exams</div>
                                <div class="text-3xl font-bold">{{ $totalExams }}</div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 p-8 rounded-2xl text-white shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <div class="flex items-center gap-4">
                            <i class="fas fa-user-graduate text-3xl text-purple-200"></i>
                            <div>
                                <a href="{{ route('admin.users.index')}}">
                                <div class="text-sm font-medium">Total Students</div>
                                <div class="text-3xl font-bold">{{ $totalStudents }}</div>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-teal-600 to-teal-700 p-8 rounded-2xl text-white shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        <div class="flex items-center gap-4">
                            <i class="fas fa-file-alt text-3xl text-teal-200"></i>
                            <div>
                                <div class="text-sm font-medium">Results Published</div>
                                <div class="text-3xl font-bold">0</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap justify-end gap-6">
                    <a href="{{ route('admin.exams.index') }}"
                       class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-8 py-3 rounded-full text-base font-bold border border-gray-200 shadow-md hover:shadow-glow transition-all duration-300">
                        View All Exams
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                       class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-8 py-3 rounded-full text-base font-bold border border-gray-200 shadow-md hover:shadow-glow transition-all duration-300">
                        View All Students
                    </a>
                    <a href=""
                       class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-8 py-3 rounded-full text-base font-bold border border-gray-200 shadow-md hover:shadow-glow transition-all duration-300">
                        View All Results
                    </a>
                </div>
            </div>

            <!-- Recent Activities and Upcoming Exams Section -->
            <div class="w-full flex flex-col lg:flex-row gap-10">
                <!-- Recent Activities Section -->
                <div class="w-full lg:w-1/2 bg-white p-8 rounded-2xl shadow-md border border-gray-200 flex flex-col h-fit animate-fade-in">
                    <div class="flex justify-between items-center mb-6 border-b border-gray-200 pb-3">
                        <h2 class="text-2xl font-semibold text-gray-800">Recent Activities</h2>
                        <a href=""
                           class="bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white px-6 py-2 rounded-full text-sm font-semibold border border-gray-200 shadow-md hover:shadow-glow transition-all duration-300">
                            View All
                        </a>
                    </div>
                    @if($recentActivities->isEmpty())
                        <p class="text-center py-4 text-sm bg-gradient-to-r from-gray-500 to-gray-600 bg-clip-text text-transparent">No recent activities found.</p>
                    @else
                        <ul class="space-y-6 max-h-[350px] overflow-y-auto pr-2">
                            @foreach($recentActivities as $activity)
                                <li class="flex items-center justify-between bg-gray-50 p-5 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-circle text-blue-500 text-xs"></i>
                                        <span class="text-sm font-medium text-gray-800">{{ $activity->activity }}</span>
                                    </div>
                                    <span class="text-xs text-gray-500 activity-time" data-time="{{ $activity->updated_at->toIso8601String() }}">
                                        {{ $activity->updated_at->diffForHumans() }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <!-- Upcoming Exams Section -->
                <div class="w-full lg:w-1/2 bg-white p-8 rounded-2xl shadow-md border border-gray-200 flex flex-col h-fit animate-fade-in">
                    <div class="flex justify-between items-center mb-6 border-b border-gray-200 pb-3">
                        <h2 class="text-2xl font-semibold text-gray-800">Upcoming Exams</h2>
                        <a href=""
                           class="bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white px-6 py-2 rounded-full text-sm font-semibold border border-gray-200 shadow-md hover:shadow-glow transition-all duration-300">
                            View All
                        </a>
                    </div>
                    @if($upcomingExams->isEmpty())
                        <p class="text-center py-4 text-sm bg-gradient-to-r from-gray-500 to-gray-600 bg-clip-text text-transparent">No upcoming exams found.</p>
                    @else
                        <ul class="space-y-6 max-h-[350px] overflow-y-auto pr-2">
                            @foreach($upcomingExams as $exam)
                                <li class="flex items-center justify-between bg-gray-50 p-5 rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-all duration-300">
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-calendar-alt text-indigo-500 text-sm"></i>
                                        <span class="text-sm font-medium text-gray-800">{{ $exam->title }}</span>
                                    </div>
                                    <span class="text-xs text-gray-500">
                                        {{ $exam->start_date->format('M d, Y H:i') }}
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
    <script>
        // Update activity timestamps
        document.querySelectorAll('.activity-time').forEach(element => {
            const time = new Date(element.dataset.time);
            element.textContent = time.toLocaleString('en-US', {
                month: 'short',
                day: 'numeric',
                hour: 'numeric',
                minute: '2-digit',
                hour12: true
            });
        });
    </script>
@endsection