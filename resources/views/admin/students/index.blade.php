<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management EDMOL</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4361ee',
                        secondary: '#3f37c9',
                        success: '#4cc9f0',
                        danger: '#f72585',
                        warning: '#f8961e',
                        dark: '#212529',
                        light: '#f8f9fa',
                        gray: '#6c757d',
                        border: '#dee2e6',
                    }
                }
            }
        }
    </script>
    <style>
        .sidebar {
            background: linear-gradient(180deg, #4361ee, #3f37c9);
        }
        
        .nav-item:hover, .nav-item.active {
            background: rgba(255,255,255,0.1);
            border-left: 4px solid white;
        }
        
        .stat-icon.users { background: rgba(67, 97, 238, 0.1); color: #4361ee; }
        .stat-icon.active { background: rgba(76, 201, 240, 0.1); color: #4cc9f0; }
        .stat-icon.pending { background: rgba(248, 150, 30, 0.1); color: #f8961e; }
        
        .status.active { background: rgba(76, 201, 240, 0.15); color: #4cc9f0; }
        .status.pending { background: rgba(248, 150, 30, 0.15); color: #f8961e; }
        .status.inactive { background: rgba(108, 117, 125, 0.15); color: #6c757d; }
        
        .action-btn.edit { background: rgba(67, 97, 238, 0.1); color: #4361ee; }
        .action-btn.edit:hover { background: #4361ee; color: white; }
        .action-btn.delete { background: rgba(247, 37, 133, 0.1); color: #f72585; }
        .action-btn.delete:hover { background: #f72585; color: white; }
        .action-btn.view { background: rgba(76, 201, 240, 0.1); color: #4cc9f0; }
        .action-btn.view:hover { background: #4cc9f0; color: white; }
        
        /* Additional styles for student management */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
        }
        .card {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }
        .card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .action-btn {
            transition: all 0.2s ease;
        }
        .action-btn:hover {
            transform: translateY(-2px);
        }
        .avatar-initials {
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #6b7280;
        }
        .search-input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.3);
        }
        .pagination .page-link {
            transition: all 0.2s ease;
        }
        .pagination .page-link:hover {
            background-color: #e5e7eb;
        }
        @media (max-width: 768px) {
            .sidebar { width: 70px; }
            .sidebar .logo h2, 
            .sidebar .nav-item span { display: none; }
            .main-content { margin-left: 70px; }
            .table-responsive {
                overflow-x: auto;
            }
            .card-header {
                flex-direction: column;
                gap: 1rem;
            }
        }
        
        /* Enhanced table styling */
        .student-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background: white;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .student-table th {
            background: linear-gradient(to bottom, #f8fafc, #f1f5f9);
            padding: 0.75rem 1.5rem;
            text-align: left;
            font-weight: 600;
            color: #374151;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            border-bottom: 1px solid #e5e7eb;
        }
        .student-table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #f3f4f6;
            color: #374151;
        }
        .student-table tr:last-child td {
            border-bottom: none;
        }
        .student-table tr:hover {
            background-color: #f9fafb;
        }







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
</head>
<body class="bg-gray-100 min-h-screen flex">
    <!-- Sidebar -->
    <div class="sidebar w-64 text-white p-4 fixed h-full shadow-lg z-50">
        <div class="sidebar-header pb-4 border-b border-white/10">
            <div class="logo flex items-center gap-3">
                <div class="bg-white/20 p-2 rounded-lg">
                    <i class="fas fa-lock text-xl"></i>
                </div>
                <h2 class="text-xl font-semibold">Admin Panel</h2>
            </div>
        </div>
        <div class="nav-links py-5">
            <a href="{{ route('admin.dashboard') }}" class="nav-item py-3 px-5 flex items-center gap-3 cursor-pointer">
                <i class="fas fa-home w-6 text-center"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.users.index') }}" class="nav-item py-3 px-5 flex items-center gap-3 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-center" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span>Users</span>
            </a>

            <a href="{{ route('admin.students.index') }}" class="nav-item active py-3 px-5 flex items-center gap-3 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-center" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span>Manage Students</span>
            </a>
            

            <a href="{{ route('admin.fees.index') }}" class="nav-item py-3 px-5 flex items-center gap-3 cursor-pointer">
                <i class="fas fa-money-bill-wave w-6 text-center"></i>
                <span>Fees Management</span>
            </a>

            <a href="{{ route('admin.grade-assignments') }}" class="nav-item py-3 px-5 flex items-center gap-3 cursor-pointer">
                <i class="fas fa-users-between-lines w-6 text-center"></i>
                <span>Grade Assignments</span>
            </a>
            
            <a href="#" class="nav-item py-3 px-5 flex items-center gap-3 cursor-pointer">
                <i class="fas fa-chalkboard w-6 text-center"></i>
                <span>Classes</span>
            </a>
            
            <a href="#" class="nav-item py-3 px-5 flex items-center gap-3 cursor-pointer">
                <i class="fas fa-book w-6 text-center"></i>
                <span>Courses</span>
            </a>

            <a href="{{ route('admin.announcements.index') }}" class="nav-item py-3 px-5 flex items-center gap-3 cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                </svg>
                <span>Announcements</span>
            </a>

            <a href="#" class="nav-item py-3 px-5 flex items-center gap-3 cursor-pointer">
                <i class="fas fa-cog w-6 text-center"></i>
                <span>Settings</span>
            </a>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-item py-3 px-5 flex items-center gap-3 cursor-pointer w-full">
                    <i class="fas fa-sign-out-alt w-6 text-center"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content ml-64 p-5 flex-grow">
       <div class="card-header flex flex-col md:flex-row justify-between items-start md:items-center gap-4 p-6">
    <h1 class="text-2xl font-bold text-gray-800">Student Management</h1>
    <a href="{{ route('admin.students.create') }}" class="flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition-colors action-btn">
        <i class="fas fa-plus-circle"></i>
        Add New Student
    </a>
</div>

<!-- Student Statistics Cards -->
<div class="stats-cards grid grid-cols-1 md:grid-cols-3 gap-6 px-6 pb-6">
    <!-- Total Students Card -->
    <div class="stat-card bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <div class="stat-icon bg-white/20 p-3 rounded-full inline-flex items-center justify-center mb-3">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold mb-1">Total Students</h3>
                <p class="text-3xl font-bold">{{ $totalStudents }}</p>
            </div>
            <div class="text-blue-100">
                <i class="fas fa-graduation-cap text-4xl opacity-70"></i>
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-blue-400/30">
            <p class="text-blue-100 text-sm flex items-center">
                <i class="fas fa-chart-line mr-2"></i>
                <span>All registered students</span>
            </p>
        </div>
    </div>

    <!-- Male Students Card -->
    <div class="stat-card bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <div class="stat-icon bg-white/20 p-3 rounded-full inline-flex items-center justify-center mb-3">
                    <i class="fas fa-male text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold mb-1">Male Students</h3>
                <p class="text-3xl font-bold">{{ $maleStudents }}</p>
            </div>
            <div class="text-indigo-100">
                <i class="fas fa-user text-4xl opacity-70"></i>
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-indigo-400/30">
            <p class="text-indigo-100 text-sm flex items-center">
                <i class="fas fa-percentage mr-2"></i>
                <span>{{ $totalStudents > 0 ? round(($maleStudents / $totalStudents) * 100, 1) : 0 }}% of total</span>
            </p>
        </div>
    </div>

    <!-- Female Students Card -->
    <div class="stat-card bg-gradient-to-r from-pink-500 to-pink-600 text-white rounded-xl p-6 shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <div class="stat-icon bg-white/20 p-3 rounded-full inline-flex items-center justify-center mb-3">
                    <i class="fas fa-female text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold mb-1">Female Students</h3>
                <p class="text-3xl font-bold">{{ $femaleStudents }}</p>
            </div>
            <div class="text-pink-100">
                <i class="fas fa-user text-4xl opacity-70"></i>
            </div>
        </div>
        <div class="mt-4 pt-3 border-t border-pink-400/30">
            <p class="text-pink-100 text-sm flex items-center">
                <i class="fas fa-percentage mr-2"></i>
                <span>{{ $totalStudents > 0 ? round(($femaleStudents / $totalStudents) * 100, 1) : 0 }}% of total</span>
            </p>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6 flex items-center">
        <i class="fas fa-check-circle mr-2"></i>
        {{ session('success') }}
    </div>
@endif
                
                <!-- Search and Filter Section -->
                <div class="mb-6 flex flex-col sm:flex-row gap-3">
                    <div class="relative flex-grow">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" id="searchInput" class="search-input pl-10 pr-4 py-2 border border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500" placeholder="Search students by name, ID, or class...">
                    </div>
                    <button class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-secondary transition-colors flex items-center gap-2">
                        <i class="fas fa-filter"></i>
                        Filters
                    </button>
                </div>
                
                <!-- Students Table -->
                <div class="table-responsive overflow-x-auto rounded-lg">
                    <table class="student-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Age</th>
                                <th>Gender</th>
                                <th>Parent Phone</th>
                                <th>Class</th>
                                <th>Admission Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="studentsTable">
                            @foreach($students as $student)
                            <tr>
                                <td class="font-medium text-gray-900">{{ $student->student_id }}</td>
                                <td>
                                    @php
                                        $initials = implode('', array_map(function($word) {
                                            return strtoupper(substr(trim($word), 0, 1));
                                        }, array_slice(explode(' ', $student->name), 0, 2)));
                                        
                                        $colors = ['bg-indigo-100', 'bg-green-100', 'bg-yellow-100', 'bg-red-100', 'bg-blue-100', 'bg-purple-100'];
                                        $colorClass = $colors[array_rand($colors)];
                                    @endphp
                                    
                                    @if($student->image)
                                        <img src="{{ asset('storage/'.$student->image) }}" 
                                             class="h-10 w-10 rounded-full object-cover border border-gray-200"
                                             alt="{{ $student->name }} avatar">
                                    @else
                                        <div class="h-10 w-10 rounded-full {{ $colorClass }} avatar-initials">
                                            {{ $initials ?: '?' }}
                                        </div>
                                    @endif
                                </td>
                                <td class="text-gray-900">{{ $student->name }}</td>
                                <td class="text-gray-900">{{ $student->age }}</td>
                                <td class="text-gray-900">{{ $student->gender }}</td>
                                <td class="text-gray-900">{{ $student->parent_phone }}</td>
                                <td class="text-gray-900">{{ $student->class_applying_for }}</td>
                                <td class="text-gray-900">{{ $student->date_of_admission->format('M d, Y') }}</td>
                                <td>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.students.show', $student) }}" class="action-btn view p-2 rounded-full">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.students.edit', $student) }}" class="action-btn edit p-2 rounded-full">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.students.destroy', $student) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-btn delete p-2 rounded-full" onclick="return confirm('Are you sure you want to delete this student?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-sm text-gray-700">
                        Showing <span class="font-medium">{{ $students->firstItem() }}</span> to <span class="font-medium">{{ $students->lastItem() }}</span> of <span class="font-medium">{{ $students->total() }}</span> results
                    </div>
                    <div class="flex space-x-2 pagination">
                        @if ($students->onFirstPage())
                            <span class="px-3 py-1 rounded-lg border border-gray-300 text-gray-400 bg-gray-100">Previous</span>
                        @else
                            <a href="{{ $students->previousPageUrl() }}" class="px-3 py-1 rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 page-link">Previous</a>
                        @endif
                        
                        @foreach ($students->getUrlRange(1, $students->lastPage()) as $page => $url)
                            @if ($page == $students->currentPage())
                                <span class="px-3 py-1 rounded-lg border border-primary text-white bg-primary page-link">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-3 py-1 rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 page-link">{{ $page }}</a>
                            @endif
                        @endforeach
                        
                        @if ($students->hasMorePages())
                            <a href="{{ $students->nextPageUrl() }}" class="px-3 py-1 rounded-lg border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 page-link">Next</a>
                        @else
                            <span class="px-3 py-1 rounded-lg border border-gray-300 text-gray-400 bg-gray-100">Next</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Highlight active nav item
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            const navItems = document.querySelectorAll('.nav-item');
            
            navItems.forEach(item => {
                const link = item.closest('a');
                if (link && link.getAttribute('href') === currentPath) {
                    item.classList.add('active');
                }
                
                item.addEventListener('click', function() {
                    navItems.forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                });
            });

            // Search functionality
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                const tableRows = document.querySelectorAll('#studentsTable tr');
                
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    
                    tableRows.forEach(row => {
                        const textContent = row.textContent.toLowerCase();
                        if (textContent.includes(searchTerm)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
        });
    </script>




</body>
</html>