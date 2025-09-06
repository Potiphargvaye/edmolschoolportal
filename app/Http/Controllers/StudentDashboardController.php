<?php

// app/Http/Controllers/StudentDashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Announcement; // Add this import

class StudentDashboardController extends Controller
{
    /**
     * Show the student dashboard
     */
    public function index()
    {
        $user = Auth::user()->load('grade'); // Eager load the grade relationship
        
        // Fetch announcements from the database
        $announcements = Announcement::where(function($query) {
                $query->where('end_date', '>=', now())
                      ->orWhereNull('end_date');
            })
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('student.dashboard', compact('user', 'announcements'));
    }
}