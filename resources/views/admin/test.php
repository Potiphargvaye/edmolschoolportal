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

























<!DOCTYPE html>
<html lang="en">
<head>
     <!-- Favicon for various devices -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon" sizes="32x32">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('kiddos-school-master/css/templatemo-glass-admin-style.css') }}">
<script src="{{ asset('kiddos-school-master/js/templatemo-glass-admin-script.js') }}"></script>

    @include('partials.admin.admin-head')
    @livewireStyles
</head>

<body class="text-gray-800 bg-[#F8FAFC] min-h-screen">

    {{-- Sidebar --}}
    @include('partials.admin.admin-sidebar')

    {{-- Mobile overlay --}}
    <div class="fixed top-0 left-0 w-full h-full bg-black/50 z-40 md:hidden sidebar-overlay hidden"></div>

    {{-- Main 
   <main class="w-full md:w-[calc(100%-256px)] md:ml-64 bg-white min-h-screen transition-all main">--}}
    <main class="w-full md:w-[calc(100%-256px)] md:ml-64 min-h-screen transition-all main bg-transparent">



        {{-- Navbar --}} 
        @include('partials.admin.admin-navbar')

        {{-- Page Content --}}
        <section class="p-6">
    <div class="dashboard-glass">
        @yield('content')
    </div>
     </section>


    </main>

    {{-- Scripts --}}
    @include('partials.admin.admin-scripts')
 @livewireScripts
</body>
</html>










<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    



<script>


    /* ============================================
   TemplateMo 3D Glassmorphism Dashboard
   https://templatemo.com
   JavaScript
============================================ */

(function() {
    'use strict';

    // ============================================
    // Theme Toggle
    // ============================================
    function initThemeToggle() {
        const themeToggle = document.getElementById('theme-toggle');
        if (!themeToggle) return;

        const iconSun = themeToggle.querySelector('.icon-sun');
        const iconMoon = themeToggle.querySelector('.icon-moon');
        
        function setTheme(theme) {
            document.documentElement.setAttribute('data-theme', theme);
            localStorage.setItem('theme', theme);
            
            if (iconSun && iconMoon) {
                if (theme === 'light') {
                    iconSun.style.display = 'none';
                    iconMoon.style.display = 'block';
                } else {
                    iconSun.style.display = 'block';
                    iconMoon.style.display = 'none';
                }
            }
        }
        
        // Check for saved theme preference or default to dark
        const savedTheme = localStorage.getItem('theme') || 'dark';
        setTheme(savedTheme);
        
        themeToggle.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            setTheme(currentTheme === 'dark' ? 'light' : 'dark');
        });
    }

    // ============================================
    // 3D Tilt Effect
    // ============================================
    function initTiltEffect() {
        document.querySelectorAll('.glass-card-3d').forEach(card => {
            card.addEventListener('mousemove', (e) => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                
                const centerX = rect.width / 2;
                const centerY = rect.height / 2;
                
                const rotateX = (y - centerY) / 20;
                const rotateY = (centerX - x) / 20;
                
                card.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) translateZ(10px)`;
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) translateZ(0)';
            });
        });
    }

    // ============================================
    // Animated Counters
    // ============================================
    function animateCounter(element, target, duration = 2000) {
        const start = 0;
        const startTime = performance.now();
        
        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            // Easing function
            const easeOut = 1 - Math.pow(1 - progress, 3);
            const current = Math.floor(start + (target - start) * easeOut);
            
            if (element.dataset.prefix) {
                element.textContent = element.dataset.prefix + current.toLocaleString() + (element.dataset.suffix || '');
            } else {
                element.textContent = current.toLocaleString() + (element.dataset.suffix || '');
            }
            
            if (progress < 1) {
                requestAnimationFrame(update);
            }
        }
        
        requestAnimationFrame(update);
    }

    function initCounters() {
        const counters = document.querySelectorAll('.stat-value');
        counters.forEach(counter => {
            const text = counter.textContent;
            const value = parseInt(text.replace(/[^0-9]/g, ''));
            
            if (text.includes('$')) {
                counter.dataset.prefix = '$';
            }
            if (text.includes('%')) {
                counter.dataset.suffix = '%';
            }
            
            animateCounter(counter, value);
        });
    }

    // ============================================
    // Mobile Menu Toggle
    // ============================================
    function initMobileMenu() {
        const menuToggle = document.querySelector('.mobile-menu-toggle');
        const sidebar = document.getElementById('sidebar');
        
        if (menuToggle && sidebar) {
            menuToggle.addEventListener('click', () => {
                sidebar.classList.toggle('open');
            });

            // Close sidebar when clicking outside
            document.addEventListener('click', (e) => {
                if (sidebar.classList.contains('open') && 
                    !sidebar.contains(e.target) && 
                    !menuToggle.contains(e.target)) {
                    sidebar.classList.remove('open');
                }
            });
        }
    }

    // ============================================
    // Form Validation (for login/register)
    // ============================================
    function initFormValidation() {
        const forms = document.querySelectorAll('form[data-validate]');
        
        forms.forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                
                let isValid = true;
                const inputs = form.querySelectorAll('.form-input[required]');
                
                inputs.forEach(input => {
                    if (!input.value.trim()) {
                        isValid = false;
                        input.style.borderColor = '#ff6b6b';
                    } else {
                        input.style.borderColor = '';
                    }
                });

                // Email validation
                const emailInput = form.querySelector('input[type="email"]');
                if (emailInput && emailInput.value) {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(emailInput.value)) {
                        isValid = false;
                        emailInput.style.borderColor = '#ff6b6b';
                    }
                }

                if (isValid) {
                    // Form is valid - you can add your submission logic here
                    console.log('Form is valid');
                    // For demo purposes, redirect to dashboard
                    if (form.dataset.redirect) {
                        window.location.href = form.dataset.redirect;
                    }
                }
            });
        });
    }

    // ============================================
    // Password Visibility Toggle
    // ============================================
    function initPasswordToggle() {
        const toggleButtons = document.querySelectorAll('.password-toggle');
        
        toggleButtons.forEach(button => {
            button.addEventListener('click', () => {
                const input = button.parentElement.querySelector('input');
                const icon = button.querySelector('svg');
                
                if (input.type === 'password') {
                    input.type = 'text';
                    icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
                } else {
                    input.type = 'password';
                    icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
                }
            });
        });
    }

    // ============================================
    // Smooth Page Transitions
    // ============================================
    function initPageTransitions() {
        const links = document.querySelectorAll('a[href$=".html"]');
        
        links.forEach(link => {
            link.addEventListener('click', (e) => {
                // Skip external links
                if (link.hostname !== window.location.hostname) return;
                
                e.preventDefault();
                const href = link.getAttribute('href');
                
                document.body.style.opacity = '0';
                document.body.style.transition = 'opacity 0.3s ease';
                
                setTimeout(() => {
                    window.location.href = href;
                }, 300);
            });
        });

        // Fade in on page load
        window.addEventListener('load', () => {
            document.body.style.opacity = '1';
        });
    }

    // ============================================
    // Settings Tab Navigation
    // ============================================
    function initSettingsTabs() {
        const tabLinks = document.querySelectorAll('.settings-nav-link[data-tab]');
        
        if (tabLinks.length === 0) return;

        tabLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                
                // Get target tab
                const tabId = link.getAttribute('data-tab');
                
                // Remove active class from all nav links
                document.querySelectorAll('.settings-nav-link').forEach(navLink => {
                    navLink.classList.remove('active');
                });
                
                // Add active class to clicked link
                link.classList.add('active');
                
                // Hide all tab contents
                document.querySelectorAll('.settings-tab-content').forEach(tab => {
                    tab.classList.remove('active');
                });
                
                // Show target tab content
                const targetTab = document.getElementById('tab-' + tabId);
                if (targetTab) {
                    targetTab.classList.add('active');
                }
            });
        });

        // Theme select sync with toggle
        const themeSelect = document.getElementById('theme-select');
        if (themeSelect) {
            const currentTheme = localStorage.getItem('theme') || 'dark';
            themeSelect.value = currentTheme;
            
            themeSelect.addEventListener('change', () => {
                const theme = themeSelect.value;
                if (theme === 'system') {
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    document.documentElement.setAttribute('data-theme', prefersDark ? 'dark' : 'light');
                } else {
                    document.documentElement.setAttribute('data-theme', theme);
                    localStorage.setItem('theme', theme);
                }
                
                // Update theme toggle icons
                const iconSun = document.querySelector('#theme-toggle .icon-sun');
                const iconMoon = document.querySelector('#theme-toggle .icon-moon');
                if (iconSun && iconMoon) {
                    const effectiveTheme = document.documentElement.getAttribute('data-theme');
                    if (effectiveTheme === 'light') {
                        iconSun.style.display = 'none';
                        iconMoon.style.display = 'block';
                    } else {
                        iconSun.style.display = 'block';
                        iconMoon.style.display = 'none';
                    }
                }
            });
        }
    }

    // ============================================
    // Initialize All Functions
    // ============================================
    function init() {
        initThemeToggle();
        initTiltEffect();
        initCounters();
        initMobileMenu();
        initFormValidation();
        initPasswordToggle();
        initPageTransitions();
        initSettingsTabs();
    }

    // Run on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }

})();


</script>
</body>
</html>


























































<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Subject;
use App\Models\Student;
class GradeAssignmentController extends Controller
{
public function index()
{
    $grades = Grade::with(['teachers' => function($query) {
        $query->select('users.id', 'name', 'registration_id')
              ->withPivot('id', 'subjects');
    }, 'students'])->get();
    
    $allTeachers = User::where('role', 'teacher')->get();
   $unassignedStudents = Student::where('status', 'registered')
                             ->whereNull('grade_id')
                             ->get();

    
    $allSubjects = \App\Models\Subject::orderBy('name')->get();
    
    // Preload subjects for display
    $subjectsMap = \App\Models\Subject::pluck('name', 'id')->toArray();
    
    // Process teachers to convert subject IDs to names
    foreach ($grades as $grade) {
        foreach ($grade->teachers as $teacher) {
            $subjectIds = json_decode($teacher->pivot->subjects, true) ?? [];
            $teacher->subjectNames = array_map(function($id) use ($subjectsMap) {
                return $subjectsMap[$id] ?? null;
            }, $subjectIds);
            $teacher->subjectNames = array_filter($teacher->subjectNames); 
        }
    }
    
    return view('admin.grade-assignments.index', compact(
        'grades',
        'allTeachers',
        'unassignedStudents',
        'allSubjects'
    ));
}

    public function assignTeacher(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'grade_id' => 'required|exists:grades,id',
            'subjects' => 'required|array|min:1'
        ]);
        
        $teacher = User::findOrFail($validated['teacher_id']);
        $grade = Grade::findOrFail($validated['grade_id']);
        
        $grade->teachers()->attach($teacher->id, [
            'subjects' => json_encode($validated['subjects'])
        ]);
        
        return back()->with('success', 'Teacher assigned successfully');
    }

    public function unassignTeacher(Request $request, $assignmentId)
{
    DB::table('teacher_grade_subject')
        ->where('id', $assignmentId)
        ->delete();

    // Check if it's an AJAX request
    if ($request->ajax() || $request->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Teacher assignment removed'
        ]);
    }
    
    return back()->with('success', 'Teacher assignment removed');
}

    public function assignHomeroomTeacher(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'grade_id' => 'required|exists:grades,id',
            'subjects' => 'required|array|min:1'
        ]);

        $teacher = User::findOrFail($validated['teacher_id']);
        
        Grade::findOrFail($validated['grade_id'])->update([
            'teacher_id' => $teacher->registration_id,
            'subjects' => json_encode($validated['subjects'])
        ]);

        return back()->with('success', 'Teacher assigned successfully');
    }

   public function assignStudent(Request $request)
{
    $validated = $request->validate([
        'student_id' => 'required|exists:students,id',
        'grade_id'   => 'required|exists:grades,id',
        'subjects'   => 'required|array|min:1',
    ]);

    $student = Student::findOrFail($validated['student_id']);

    $student->update([
        'grade_id' => $validated['grade_id'],
        'subjects' => json_encode($validated['subjects']),
    ]);

    return back()->with('success', 'Student assigned successfully');
}
    public function unassignStudent(Request $request, User $student)
{
    $student->update([
        'grade_id' => null,
        'subjects' => null
    ]);

    // Check if it's an AJAX request
    if ($request->ajax() || $request->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Student unassigned successfully'
        ]);
    }

    return back()->with('success', 'Student unassigned successfully');
}

    public function updateSubjects(Request $request, Grade $grade)
    {
        $validated = $request->validate([
            'subjects' => 'required|array',
            'subjects.*' => 'string|max:255'
        ]);
        
        $subjects = array_values(array_filter($validated['subjects'], function($subject) {
            return !empty(trim($subject));
        }));
        
        $grade->update(['subjects' => $subjects]);
        
        return back()->with('success', 'Subjects updated successfully');
    }





    // app/Http/Controllers/GradeAssignmentController.php

public function create()
{
    // Replace hardcoded array with database fetch
    $allSubjects = Subject::orderBy('name')->pluck('name', 'id');
    
    return view('grade-assignments.create', compact('allSubjects'));
}

// Add methods for managing subjects
public function getSubjects()
{
    $subjects = Subject::orderBy('name')->get();
    return response()->json($subjects);
}
// for the edit subget model 
public function getSubject($id)
{
    try {
        $subject = Subject::findOrFail($id);
        return response()->json($subject);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Subject not found'
        ], 404);
    }
}

public function storeSubject(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:subjects,name',
        'code' => 'nullable|string|max:50|unique:subjects,code',
        'description' => 'nullable|string'
    ]);

    $subject = Subject::create($request->all());

    return response()->json([
        'success' => true,
        'subject' => $subject,
        'message' => 'Subject added successfully!'
    ]);
}

public function updateSubject(Request $request, $id)
{
    try {
        $subject = Subject::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name,' . $subject->id,
            'code' => 'nullable|string|max:50|unique:subjects,code,' . $subject->id,
            'description' => 'nullable|string'
        ]);

        $subject->update($request->all());

        return response()->json([
            'success' => true,
            'subject' => $subject,
            'message' => 'Subject updated successfully!'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error updating subject: ' . $e->getMessage()
        ], 500);
    }
}
    
}



























































































































<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Subject;

class GradeAssignmentController extends Controller
{
public function index()
{
    $grades = Grade::with(['teachers' => function($query) {
        $query->select('users.id', 'name', 'registration_id')
              ->withPivot('id', 'subjects');
    }, 'students'])->get();
    
    $allTeachers = User::where('role', 'teacher')->get();
    $unassignedStudents = User::where('role', 'student')
                            ->whereNull('grade_id')
                            ->get();
    
    $allSubjects = \App\Models\Subject::orderBy('name')->get();
    
    // Preload subjects for display
    $subjectsMap = \App\Models\Subject::pluck('name', 'id')->toArray();
    
    // Process teachers to convert subject IDs to names
    foreach ($grades as $grade) {
        foreach ($grade->teachers as $teacher) {
            $subjectIds = json_decode($teacher->pivot->subjects, true) ?? [];
            $teacher->subjectNames = array_map(function($id) use ($subjectsMap) {
                return $subjectsMap[$id] ?? null;
            }, $subjectIds);
            $teacher->subjectNames = array_filter($teacher->subjectNames); 
        }
    }
    
    return view('admin.grade-assignments.index', compact(
        'grades',
        'allTeachers',
        'unassignedStudents',
        'allSubjects'
    ));
}

    public function assignTeacher(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'grade_id' => 'required|exists:grades,id',
            'subjects' => 'required|array|min:1'
        ]);
        
        $teacher = User::findOrFail($validated['teacher_id']);
        $grade = Grade::findOrFail($validated['grade_id']);
        
        $grade->teachers()->attach($teacher->id, [
            'subjects' => json_encode($validated['subjects'])
        ]);
        
        return back()->with('success', 'Teacher assigned successfully');
    }

    public function unassignTeacher(Request $request, $assignmentId)
{
    DB::table('teacher_grade_subject')
        ->where('id', $assignmentId)
        ->delete();

    // Check if it's an AJAX request
    if ($request->ajax() || $request->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Teacher assignment removed'
        ]);
    }
    
    return back()->with('success', 'Teacher assignment removed');
}

    public function assignHomeroomTeacher(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:users,id',
            'grade_id' => 'required|exists:grades,id',
            'subjects' => 'required|array|min:1'
        ]);

        $teacher = User::findOrFail($validated['teacher_id']);
        
        Grade::findOrFail($validated['grade_id'])->update([
            'teacher_id' => $teacher->registration_id,
            'subjects' => json_encode($validated['subjects'])
        ]);

        return back()->with('success', 'Teacher assigned successfully');
    }

    public function assignStudent(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'grade_id' => 'required|exists:grades,id',
            'subjects' => 'required|array|min:1'
        ]);

        $student = User::findOrFail($validated['student_id']);
        $student->update([
            'grade_id' => $validated['grade_id'],
            'subjects' => json_encode($validated['subjects'])
        ]);

        return back()->with('success', 'Student assigned successfully');
    }

    public function unassignStudent(Request $request, User $student)
{
    $student->update([
        'grade_id' => null,
        'subjects' => null
    ]);

    // Check if it's an AJAX request
    if ($request->ajax() || $request->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Student unassigned successfully'
        ]);
    }

    return back()->with('success', 'Student unassigned successfully');
}

    public function updateSubjects(Request $request, Grade $grade)
    {
        $validated = $request->validate([
            'subjects' => 'required|array',
            'subjects.*' => 'string|max:255'
        ]);
        
        $subjects = array_values(array_filter($validated['subjects'], function($subject) {
            return !empty(trim($subject));
        }));
        
        $grade->update(['subjects' => $subjects]);
        
        return back()->with('success', 'Subjects updated successfully');
    }





    // app/Http/Controllers/GradeAssignmentController.php

public function create()
{
    // Replace hardcoded array with database fetch
    $allSubjects = Subject::orderBy('name')->pluck('name', 'id');
    
    return view('grade-assignments.create', compact('allSubjects'));
}

// Add methods for managing subjects
public function getSubjects()
{
    $subjects = Subject::orderBy('name')->get();
    return response()->json($subjects);
}
// for the edit subget model 
public function getSubject($id)
{
    try {
        $subject = Subject::findOrFail($id);
        return response()->json($subject);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Subject not found'
        ], 404);
    }
}

public function storeSubject(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:subjects,name',
        'code' => 'nullable|string|max:50|unique:subjects,code',
        'description' => 'nullable|string'
    ]);

    $subject = Subject::create($request->all());

    return response()->json([
        'success' => true,
        'subject' => $subject,
        'message' => 'Subject added successfully!'
    ]);
}

public function updateSubject(Request $request, $id)
{
    try {
        $subject = Subject::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255|unique:subjects,name,' . $subject->id,
            'code' => 'nullable|string|max:50|unique:subjects,code,' . $subject->id,
            'description' => 'nullable|string'
        ]);

        $subject->update($request->all());

        return response()->json([
            'success' => true,
            'subject' => $subject,
            'message' => 'Subject updated successfully!'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error updating subject: ' . $e->getMessage()
        ], 500);
    }
}
    
}








