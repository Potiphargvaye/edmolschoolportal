<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        
        $allSubjects = ['Math', 'English', 'Biology', 'Physics', 'Chemistry', 'History', 'Geography, Web Development'];
        
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

    public function unassignTeacher($assignmentId)
    {
        DB::table('teacher_grade_subject')
            ->where('id', $assignmentId)
            ->delete();
        
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

    public function unassignStudent(User $student)
    {
        $student->update([
            'grade_id' => null,
            'subjects' => null
        ]);

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
}
