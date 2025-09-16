<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    public function index()
{
    $students = Student::latest()->paginate(10);
    
    // Calculate statistics
    $totalStudents = Student::count();
    $maleStudents = Student::where('gender', 'male')->count();
    $femaleStudents = Student::where('gender', 'female')->count();
    
    return view('admin.students.index', compact(
        'students',
        'totalStudents',
        'maleStudents',
        'femaleStudents' 
    ));
}

    public function create()
    {
        return view('admin.students.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:5|max:25',
            'gender' => 'required|in:Male,Female,Other',
            'parent_phone' => 'required|string|max:15',
            'class_applying_for' => 'required|string|max:100',
            'date_of_admission' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'transcript' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'recommendation_letter' => 'nullable|file|mimes:pdf,doc,docx|max:5120'
        ]);

        $data = $request->except(['_token', 'image', 'transcript', 'recommendation_letter']);

        // Handle file uploads
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('students/images', 'public');
        }

        if ($request->hasFile('transcript')) {
            $data['transcript'] = $request->file('transcript')->store('students/transcripts', 'public');
        }

        if ($request->hasFile('recommendation_letter')) {
            $data['recommendation_letter'] = $request->file('recommendation_letter')->store('students/recommendations', 'public');
        }

        Student::create($data);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student registered successfully.');
    }

    public function show(Student $student)
    {
        return view('admin.students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:5|max:25',
            'gender' => 'required|in:Male,Female,Other',
            'parent_phone' => 'required|string|max:15',
            'class_applying_for' => 'required|string|max:100',
            'date_of_admission' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'transcript' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'recommendation_letter' => 'nullable|file|mimes:pdf,doc,docx|max:5120'
        ]);

        $data = $request->except(['_token', '_method', 'image', 'transcript', 'recommendation_letter']);

        // Handle file uploads
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($student->image) {
                Storage::disk('public')->delete($student->image);
            }
            $data['image'] = $request->file('image')->store('students/images', 'public');
        }

        if ($request->hasFile('transcript')) {
            if ($student->transcript) {
                Storage::disk('public')->delete($student->transcript);
            }
            $data['transcript'] = $request->file('transcript')->store('students/transcripts', 'public');
        }

        if ($request->hasFile('recommendation_letter')) {
            if ($student->recommendation_letter) {
                Storage::disk('public')->delete($student->recommendation_letter);
            }
            $data['recommendation_letter'] = $request->file('recommendation_letter')->store('students/recommendations', 'public');
        }

        $student->update($data);

        return redirect()->route('admin.students.index')
            ->with('success', 'Student updated successfully.');
    }

    public function destroy(Student $student)
    {
        // Delete associated files
        if ($student->image) {
            Storage::disk('public')->delete($student->image);
        }
        if ($student->transcript) {
            Storage::disk('public')->delete($student->transcript);
        }
        if ($student->recommendation_letter) {
            Storage::disk('public')->delete($student->recommendation_letter);
        }

        $student->delete();

        return redirect()->route('admin.students.index')
            ->with('success', 'Student deleted successfully.');
    }
}