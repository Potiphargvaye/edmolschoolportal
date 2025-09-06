@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-bold text-gray-800">Grade Assignments</h1>
    </div>

    <!-- Alerts (success/error messages) -->
    @include('partials.alerts')

    <!-- Assign Teacher Card -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4 text-gray-700">Assign Teacher</h2>
        <form action="{{ route('admin.assign-teacher') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Teacher</label>
                    <select name="teacher_id" class="w-full rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Teacher</option>
                        @foreach($allTeachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->name }} ({{ $teacher->registration_id }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Grade</label>
                    <select name="grade_id" class="w-full rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Grade</option>
                        @foreach($grades as $grade)
                            <option value="{{ $grade->id }}">Grade {{ $grade->level }}{{ $grade->section }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Subjects</label>
                    <select name="subjects[]" multiple class="w-full rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 select2">
                        @foreach($allSubjects as $subject)
                            <option value="{{ $subject }}">{{ $subject }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition duration-200">
                Assign Teacher
            </button>
        </form>
    </div>

    <!-- Assign Student Card -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4 text-gray-700">Assign Student</h2>
        <form action="{{ route('admin.assign-student') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Student</label>
                    <select name="student_id" class="w-full rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Student</option>
                        @foreach($unassignedStudents as $student)
                            <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->registration_id }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Grade</label>
                    <select name="grade_id" class="w-full rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Grade</option>
                        @foreach($grades as $grade)
                            <option value="{{ $grade->id }}">Grade {{ $grade->level }}{{ $grade->section }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-1">Subjects</label>
                    <select name="subjects[]" multiple class="w-full rounded border border-gray-300 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 select2">
                        @foreach($allSubjects as $subject)
                            <option value="{{ $subject }}">{{ $subject }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition duration-200">
                Assign Student
            </button>
        </form>
    </div>

    <!-- Current Assignments Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-700">Current Assignments</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teachers</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Students</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($grades as $grade)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            Grade {{ $grade->level }}{{ $grade->section }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($grade->teachers->count() > 0)
                                <ul class="space-y-2">ss
                                    @foreach($grade->teachers as $teacher)
                                    <li class="flex flex-col">
                                        <div class="flex items-center justify-between">
                                            <span>
                                                {{ $teacher->name }}<br>
                                                <small class="text-gray-400">ID: {{ $teacher->registration_id }}</small>
                                            </span>
                                            <form method="POST" action="{{ route('admin.unassign-teacher', ['assignment' => $teacher->pivot->id]) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="ml-2 p-1 text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 rounded-full transition"
                                                        onclick="return confirm('Remove this assignment?')"
                                                        title="Remove Assignment">
                                                    <i class="fas fa-user-minus text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                        @php
                                            $subjects = json_decode($teacher->pivot->subjects, true) ?? [];
                                        @endphp
                                        @if(!empty($subjects))
                                        <div class="mt-1">
                                            <span class="text-xs font-medium">Subjects:</span>
                                            <ul class="list-disc list-inside pl-2 text-xs">
                                                @foreach($subjects as $subject)
                                                <li>{{ $subject }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        @endif
                                    </li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-gray-400">No teachers assigned</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($grade->students->count() > 0)
                                <ul class="space-y-2">
                                    @foreach($grade->students as $student)
                                    <li class="flex items-center justify-between">
                                        <span>
                                            {{ $student->name }}<br>
                                            <small class="text-gray-400">ID: {{ $student->registration_id }}</small>
                                        </span>
                                        <form method="POST" action="{{ route('admin.unassign-student', $student) }}">
                                            @csrf
                                            <button type="submit" 
                                                    class="ml-2 p-1 text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 rounded-full transition"
                                                    onclick="return confirm('Unassign this student?')"
                                                    title="Unassign Student">
                                                <i class="fas fa-user-minus text-xs"></i>
                                            </button>
                                        </form>
                                    </li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-gray-400">No students</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <button onclick="openEditModal('{{ $grade->id }}', '{{ json_encode($grade->subjects ?? []) }}')"
                                    class="text-blue-600 hover:text-blue-900 mr-3"
                                    title="Edit Subjects">
                                <i class="fas fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                            No grade assignments yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Edit Subjects Modal -->
<div id="editSubjectsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-700">Edit Subjects</h2>
        </div>
        <form id="editSubjectsForm" method="POST" action="" class="p-6">
            @csrf
            @method('PUT')
            <div id="subjectsContainer" class="space-y-3"></div>
            <div class="flex justify-end space-x-3 mt-6">
                <button type="button" onclick="addSubjectField()" class="text-blue-500">
                    <i class="fas fa-plus mr-1"></i> Add Subject
                </button>
                <button type="button" onclick="closeEditModal()" 
                        class="bg-gray-600 text-white px-4 py-2 rounded text-sm">
                    Cancel
                </button>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded text-sm">
                    Save
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openEditModal(gradeId, subjects) {
        const modal = document.getElementById('editSubjectsModal');
        const form = document.getElementById('editSubjectsForm');
        const container = document.getElementById('subjectsContainer');
        
        form.action = `/admin/grades/${gradeId}/subjects`;
        container.innerHTML = '';
        
        try {
            // Parse the JSON string if it's a string
            let parsedSubjects = typeof subjects === 'string' ? JSON.parse(subjects) : subjects;
            
            if (Array.isArray(parsedSubjects) && parsedSubjects.length > 0) {
                parsedSubjects.forEach(subject => {
                    addSubjectField(subject);
                });
            } else {
                // Add at least one empty field
                addSubjectField();
            }
            
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        } catch (error) {
            console.error('Error parsing subjects:', error);
            // Fallback - add one empty field
            addSubjectField();
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }
    
    function closeEditModal() {
        document.getElementById('editSubjectsModal').classList.add('hidden');
        document.body.style.overflow = '';
    }
    
    function addSubjectField(value = '') {
        const container = document.getElementById('subjectsContainer');
        const div = document.createElement('div');
        div.className = 'flex items-center mb-2';
        div.innerHTML = `
            <input type="text" name="subjects[]" value="${value}" 
                   class="flex-grow rounded border border-gray-300 px-3 py-2">
            <button type="button" onclick="this.parentElement.remove()" 
                    class="ml-2 text-red-500">
                <i class="fas fa-times"></i>
            </button>
        `;
        container.appendChild(div);
    }
    
    // Close modal when clicking outside
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('editSubjectsModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeEditModal();
                }
            });
        }
    });
</script>
@endsection
