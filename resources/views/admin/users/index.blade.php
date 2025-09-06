@extends('layouts.admin')

@section('content')
@php
use Illuminate\Support\Facades\Storage;
@endphp
   
   
    <style>
        /* Statistics Cards Styles */
        .stats-cards {
            animation: fadeInUp 0.6s ease-out;
        }

        .stat-card {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: linear-gradient(transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: rotate(30deg);
            transition: all 0.6s ease;
        }

        .stat-card:hover::before {
            animation: shimmer 1.5s ease-in-out;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .stat-icon {
            transition: all 0.3s ease;
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.1);
            background: rgba(255, 255, 255, 0.3) !important;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes shimmer {
            0% {
                transform: rotate(30deg) translate(-10%, -10%);
            }
            100% {
                transform: rotate(30deg) translate(100%, 100%);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .stats-cards {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .stat-card {
                padding: 1.5rem;
            }
            
            .stat-card .text-3xl {
                font-size: 1.875rem;
            }
        }
    </style>

<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <!-- Stats Grid -->
        <div class="stats-cards grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Users Card -->
            <div class="stat-card bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl p-6 shadow-lg">
                <div class="flex items-center">
                    <div class="stat-icon bg-white/20 p-3 rounded-full text-white mr-4">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Total Users</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $totalUsers }}</h3>
                    </div>
                </div>
            </div>
            
            <!-- Teachers Card -->
            <div class="stat-card bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl p-6 shadow-lg">
                <div class="flex items-center">
                    <div class="stat-icon bg-white/20 p-3 rounded-full text-white mr-4">
                        <i class="fas fa-chalkboard-teacher text-xl"></i>
                    </div>
                    <div>
                         <p class="text-sm text-gray-600 font-medium">Teachers</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $totalTeachers }}</h3>
                    </div>
                </div>
            </div>
            
            <!-- Students Card -->
            <div class="stat-card bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-xl p-6 shadow-lg">
                <div class="flex items-center">
                    <div class="stat-icon bg-white/20 p-3 rounded-full text-white mr-4">
                        <i class="fas fa-user-graduate text-xl"></i>
                    </div>
                    <div>
                       <p class="text-sm text-gray-600 font-medium">Students</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $totalStudents }}</h3>
                    </div>
                </div>
            </div>
            
            <!-- Administrators Card -->
            <div class="stat-card bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl p-6 shadow-lg">
                <div class="flex items-center">
                    <div class="stat-icon bg-white/20 p-3 rounded-full text-white mr-4">
                        <i class="fas fa-user-shield text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Administrators</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $totalAdmins }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Bar with Search -->
        <div class="top-bar bg-white rounded-xl shadow-sm p-5 mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div class="flex justify-between items-center w-full md:w-auto">
                <h1 class="text-2xl font-bold text-gray-800">Users Management</h1>
            </div>
            
            <div class="flex flex-col md:flex-row items-start md:items-center gap-4 w-full md:w-auto">
                <!-- Search Bar -->
                <div class="search-bar flex items-center bg-gray-100 py-2 px-4 rounded-full w-full md:w-96">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" id="searchInput" placeholder="Search users..." 
                           class="bg-transparent py-1 px-3 w-full focus:outline-none search-input">
                </div>
                
                <a href="{{ route('register') }}" class="flex items-center gap-2 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors whitespace-nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add New User
                </a>
                
               <!-- Admin Profile -->
          <div class="user-profile cursor-pointer">
        @if(auth()->user()->image)
        <img src="{{ asset('storage/'.auth()->user()->image) }}" 
             alt="Admin" 
             class="w-14 h-14 rounded-full object-cover border-2 border-indigo-300 shadow-sm">
        @else
        <div class="w-14 h-14 rounded-full bg-gradient-to-br from-indigo-100 to-purple-100 text-indigo-700 flex items-center justify-center font-bold text-xl shadow-sm">
            {{ substr(auth()->user()->name, 0, 1) }}
        </div>
    @endif
    </div>
        </div>
    </div>
        
        <!-- Additional content would go here -->
    </div>

    <script>
        // Simple animation for the cards when page loads
        document.addEventListener('DOMContentLoaded', function() {
            const statCards = document.querySelectorAll('.stat-card');
            
            // Add a slight delay between each card animation
            statCards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = 1;
                    card.style.transform = 'translateY(0)';
                }, 100 * index);
            });
        });
    </script>



    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <!-- Table Headers -->
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reg ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Verified</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                
                <!-- Table Body -->
                <tbody class="bg-white divide-y divide-gray-200" id="usersTable">
                    @foreach($users as $user)
                    <tr class="user-row hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $initials = implode('', array_map(function($word) {
                                    return strtoupper(substr(trim($word), 0, 1));
                                }, array_slice(explode(' ', $user->name), 0, 2)));
                                
                                $colors = ['bg-indigo-100', 'bg-green-100', 'bg-yellow-100', 'bg-red-100', 'bg-blue-100', 'bg-purple-100'];
                                $colorClass = $colors[array_rand($colors)];
                            @endphp

                            @if($user->image)
                                <img src="{{ asset('storage/'.$user->image) }}" 
                                     class="h-10 w-10 rounded-full object-cover border border-gray-200"
                                     alt="{{ $user->name }} avatar">
                            @else
                                <div class="h-10 w-10 rounded-full {{ $colorClass }} flex items-center justify-center font-semibold text-gray-600">
                                    {{ $initials ?: '?' }}
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->registration_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $user->email }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($user->email_verified_at)
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Verified
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Not Verified
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $user->role == 'admin' ? 'bg-purple-100 text-purple-800' : 
                                   ($user->role == 'teacher' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($user->grade)
                                Grade {{ $user->grade->level }}-{{ $user->grade->section }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 transition-colors" onclick="return confirm('Are you sure you want to delete this user?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0V3a1 1 0 00-1-1h-2a1 1 0 00-1 1v1m10 0h-4" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $users->links() }}
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const userRows = document.querySelectorAll('#usersTable .user-row');
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            
            userRows.forEach(row => {
                const textContent = row.textContent.toLowerCase();
                if (searchTerm === '' || textContent.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
        
        // Clear search when escape key is pressed
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                this.value = '';
                userRows.forEach(row => {
                    row.style.display = '';
                });
            }
        });
    });
</script>



<script>
     // this is the script for the students card statistuc display 
        // Add hover effects using JavaScript
        document.addEventListener('DOMContentLoaded', function() {
            const statsCards = document.querySelectorAll('.stats-card');
            
            statsCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.classList.add('hover-shock');
                });
                
                card.addEventListener('mouseleave', function() {
                    this.classList.remove('hover-shock');
                });
            });
        });
    </script>

<style>
    .search-input:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.3);
    }
    .user-row {
        transition: all 0.2s ease;
    }
    .user-row:hover {
        background-color: #f9fafb;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
</style>
@endsection