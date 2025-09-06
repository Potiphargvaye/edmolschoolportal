<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard EDMOL</title>
    
    <!-- Favicon for various devices -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" sizes="32x32">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    
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
        
        .stat-icon.users { background: rgba(67, 97, 238, 0.1); color: '#4361ee'; }
        .stat-icon.active { background: rgba(76, 201, 240, 0.1); color: '#4cc9f0'; }
        .stat-icon.pending { background: rgba(248, 150, 30, 0.1); color: '#f8961e'; }
        
        .status.active { background: rgba(76, 201, 240, 0.15); color: '#4cc9f0'; }
        .status.pending { background: rgba(248, 150, 30, 0.15); color: '#f8961e'; }
        .status.inactive { background: rgba(108, 117, 125, 0.15); color: '#6c757d'; }
        
        .action-btn.edit { background: rgba(67, 97, 238, 0.1); color: '#4361ee'; }
        .action-btn.edit:hover { background: '#4361ee'; color: white; }
        .action-btn.delete { background: rgba(247, 37, 133, 0.1); color: '#f72585'; }
        .action-btn.delete:hover { background: '#f72585'; color: white; }
        .action-btn.view { background: rgba(76, 201, 240, 0.1); color: '#4cc9f0'; }
        .action-btn.view:hover { background: '#4cc9f0'; color: white; }
        
        @media (max-width: 768px) {
            .sidebar { width: 70px; }
            .sidebar .logo h2, 
            .sidebar .nav-item span { display: none; }
            .main-content { margin-left: 70px; }
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
            <a href="{{ route('admin.dashboard') }}" class="nav-item active py-3 px-5 flex items-center gap-3 cursor-pointer">
                <i class="fas fa-home w-6 text-center"></i>
                <span>Dashboard</span>

             <a href="{{ route('admin.users.index') }}" class="nav-item py-3 px-5 flex items-center gap-3 cursor-pointer">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-center" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
    </svg>
    <span>Users</span>
</a>


 <a href="{{ route('admin.students.index') }}" class="nav-item py-3 px-5 flex items-center gap-3 cursor-pointer">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-center" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
    </svg>
    <span>Students</span>
</a>

<a href="{{ route('admin.fees.index') }}" class="nav-item py-3 px-5 flex items-center gap-3 cursor-pointer">
                <i class="fas fa-money-bill-wave w-6 text-center"></i>
                <span>Fees Management</span>
            </a>


            <!-- Add this new item -->
    <a href="{{ route('admin.grade-assignments') }}" class="nav-item py-3 px-5 flex items-center gap-3 cursor-pointer">
   <i class="fas fa-users-between-lines w-6 text-center"></i>
    <span>Grade Assignments</span>
</a>
            <a href="#" class="nav-item py-3 px-5 flex items-center gap-3 cursor-pointer">
                <i class="fas fa-chalkboard w-6 text-center"></i>
                <span>Classes</span>
            </a>
            

<a href="{{ route('admin.announcements.index') }}" class="nav-item py-3 px-5 flex items-center gap-3 cursor-pointer {{ request()->is('admin/announcements*') ? 'active' : '' }}">
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
        <!-- Content Section -->
        @include('partials.alerts')
        @yield('content')
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
        });
    </script>
</body>
</html>