<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Grade;
use App\Models\TeacherMaterial; //change
use App\Models\TeacherGradeSubject;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Add this import
use Illuminate\Support\Facades\Storage; // Add this line



class TeacherMaterialController extends Controller
{
    use AuthorizesRequests; 
    public function index()
    {
        $teacher = Auth::user();
        $materials = TeacherMaterial::where('teacher_id', $teacher->id)
            ->with('grade')
            ->latest()
            ->paginate(10);

        $grades = TeacherGradeSubject::where('teacher_id', $teacher->id)
            ->with('grade')
            ->get()
            ->pluck('grade')
            ->unique();

        return view('teacher.materials.index', compact('materials', 'grades'));
    }

    public function create()
    {
        $teacher = Auth::user();
        $grades = TeacherGradeSubject::where('teacher_id', $teacher->id)
            ->with('grade')
            ->get()
            ->pluck('grade')
            ->unique();

        return view('teacher.materials.create', compact('grades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:assignment,note,resource',
            'grade_id' => 'required|exists:grades,id',
            'file' => 'nullable|file|mimes:pdf,doc,docx,txt,jpg,png|max:2048',
            'due_date' => 'nullable|date',
            'max_score' => 'nullable|integer|min:0'
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('teacher-materials', 'public');
        }

        TeacherMaterial::create([
            'teacher_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'grade_id' => $request->grade_id,
            'file_path' => $filePath,
            'due_date' => $request->due_date,
            'max_score' => $request->max_score,
            'is_published' => $request->has('is_published')
        ]);

        return redirect()->route('teacher.materials.index')
            ->with('success', 'Material created successfully!');
    }

    public function edit(TeacherMaterial $material)
    {
        $this->authorize('update', $material);
        
        $teacher = Auth::user();
        $grades = TeacherGradeSubject::where('teacher_id', $teacher->id)
            ->with('grade')
            ->get()
            ->pluck('grade')
            ->unique();

        return view('teacher.materials.edit', compact('material', 'grades'));
    }

    public function update(Request $request, TeacherMaterial $material)
    {
        $this->authorize('update', $material);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:assignment,note,resource',
            'grade_id' => 'required|exists:grades,id',
            'file' => 'nullable|file|mimes:pdf,doc,docx,txt,jpg,png|max:2048',
            'due_date' => 'nullable|date',
            'max_score' => 'nullable|integer|min:0'
        ]);

        if ($request->hasFile('file')) {
            // Delete old file if exists
            if ($material->file_path) {
                Storage::disk('public')->delete($material->file_path);
            }
            $filePath = $request->file('file')->store('teacher-materials', 'public');
            $material->file_path = $filePath;
        }

        $material->update([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'grade_id' => $request->grade_id,
            'due_date' => $request->due_date,
            'max_score' => $request->max_score,
            'is_published' => $request->has('is_published')
        ]);

        return redirect()->route('teacher.materials.index')
            ->with('success', 'Material updated successfully!');
    }

    public function destroy(TeacherMaterial $material)
    {
        $this->authorize('delete', $material);

        if ($material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }

        $material->delete();

        return redirect()->route('teacher.materials.index')
            ->with('success', 'Material deleted successfully!');
    }

    public function togglePublish(TeacherMaterial $material)
    {
        $this->authorize('update', $material);

        $material->update([
            'is_published' => !$material->is_published
        ]);

        return back()->with('success', 'Material status updated!');
    }
}