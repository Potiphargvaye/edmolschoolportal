<!-- resources/views/dashboard/admin.blade.php -->
@extends('layouts.admin')

@section('content')
<!-- Full-Width Header Banner -->
<div class="welcome-banner bg-gradient-to-r from-blue-600 to-purple-700 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col md:flex-row items-center justify-between">
            <div class="flex items-center mb-4 md:mb-0">
                <div class="admin-avatar mr-5">
                    @if(Auth::user()->image)
                        <img src="{{ asset('storage/' . Auth::user()->image) }}" alt="{{ Auth::user()->name }}" class="w-16 h-16 md:w-20 md:h-20 rounded-full object-cover border-4 border-white/30 shadow-md">
                    @else
                        <div class="w-16 h-16 md:w-20 md:h-20 rounded-full bg-white/20 flex items-center justify-center border-4 border-white/30 shadow-md">
                            <span class="text-white text-2xl font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                        </div>
                    @endif
                </div>
                <div class="text-white">
                    <h1 class="text-2xl md:text-3xl font-bold mb-1">Administrator Dashboard</h1>
                    <p class="text-blue-100 text-lg">Welcome, {{ Auth::user()->name }}!</p>
                </div>
            </div>
            <div class="admin-status bg-white/20 py-2 px-4 rounded-full">
                <span class="text-white text-sm flex items-center">
                    <span class="w-3 h-3 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                    Online
                </span>
            </div>
        </div>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users Card -->
        <div class="stats-card bg-white rounded-xl p-6 shadow-md border-l-4 border-blue-500 hover-shock">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-medium">Total Users</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $totalUsers }}</h3>
                </div>
            </div>
        </div>
        
        <!-- Teachers Card -->
        <div class="stats-card bg-white rounded-xl p-6 shadow-md border-l-4 border-green-500 hover-shock">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                    <i class="fas fa-chalkboard-teacher text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-medium">Teachers</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $totalTeachers }}</h3>
                </div>
            </div>
        </div>
        
        <!-- Students Card -->
        <div class="stats-card bg-white rounded-xl p-6 shadow-md border-l-4 border-purple-500 hover-shock">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                    <i class="fas fa-user-graduate text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-medium">Students</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $totalStudents }}</h3>
                </div>
            </div>
        </div>
        
        <!-- Administrators Card -->
        <div class="stats-card bg-white rounded-xl p-6 shadow-md border-l-4 border-red-500 hover-shock">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600 mr-4">
                    <i class="fas fa-user-shield text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600 font-medium">Administrators</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $totalAdmins }}</h3>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity Section -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-xl font-semibold text-gray-800">Recent Activity</h2>
        </div>
        <div class="p-6">
            <div class="flex flex-col items-center justify-center py-8 text-gray-400">
                <i class="fas fa-history text-4xl mb-3"></i>
                <p>No recent activity to display</p>
            </div>
        </div>
    </div>
</div>

<style>
    /* Welcome Banner Styles */
.welcome-banner {
    background: linear-gradient(135deg, #4678e5ff 0%, #2b58dfff 100%);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    margin: -20px -20px 20px -20px;
    padding: 20px;
    width: calc(100% + 40px);
}

@media (min-width: 640px) {
    .welcome-banner {
        margin: -24px -24px 24px -24px;
        padding: 24px;
        width: calc(100% + 48px);
    }
}

@media (min-width: 1024px) {
    .welcome-banner {
        margin: -32px -32px 32px -32px;
        padding: 32px;
        width: calc(100% + 64px);
    }
}
    /* Stats Card Hover Animation */
    .stats-card {
        transition: all 0.3s ease;
    }
    
    .hover-shock:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        animation: shock 0.5s ease;
    }
    
    @keyframes shock {
        0% { transform: translateY(-5px); }
        25% { transform: translateY(-7px) rotate(1deg); }
        50% { transform: translateY(-5px) rotate(-1deg); }
        75% { transform: translateY(-7px) rotate(1deg); }
        100% { transform: translateY(-5px); }
    }
    
    /* Admin avatar animation */
    .admin-avatar img, .admin-avatar div {
        transition: all 0.3s ease;
    }
    
    .admin-avatar:hover img, .admin-avatar:hover div {
        transform: scale(1.05);
        box-shadow: 0 0 0 4px rgba(255, 255, 255, 0.4);
    }
</style>
@endsection