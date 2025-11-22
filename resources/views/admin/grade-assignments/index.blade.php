
@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Banner Header -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-6 mb-6 rounded-xl shadow-sm">
        <h1 class="text-2xl sm:text-3xl font-bold">Grade Assignments Management</h1>
        <p class="text-indigo-100 mt-2">Manage teacher and student grade assignments</p>
    </div>

    <div class="admin-container">
        <!-- Assign Teacher Card -->
        <div class="bg-white rounded-lg shadow p-6 mb-6 border border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-chalkboard-teacher text-indigo-600"></i>
                Assign Teacher to Grade
            </h2>
            <form action="{{ route('admin.assign-teacher') }}" method="POST" id="assignTeacherForm" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Teacher</label>
                        <select name="teacher_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg" required>
                            <option value="">Select Teacher</option>
                            @foreach($allTeachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }} ({{ $teacher->registration_id }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Grade</label>
                        <select name="grade_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg" required>
                            <option value="">Select Grade</option>
                            @foreach($grades as $grade)
                                <option value="{{ $grade->id }}">Grade {{ $grade->level }}{{ $grade->section }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subjects</label>
                       <select name="subjects[]" id="subject_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="">Select a subject</option>
            @foreach($allSubjects as $subject)
                 <option value="{{ $subject->id }}">{{ $subject->name }}</option>
            @endforeach
        </select>
                    </div>
                </div>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition-all duration-200 font-semibold flex items-center gap-2 shadow-md hover:shadow-lg">
                    <i class="fas fa-user-plus"></i>
                    Assign Teacher
                </button>
            </form>
        </div>

        <!-- Assign Student Card -->
        <div class="bg-white rounded-lg shadow p-6 mb-6 border border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-user-graduate text-green-600"></i>
                Assign Student to Grade
            </h2>
            <form action="{{ route('admin.assign-student') }}" method="POST" id="assignStudentForm" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Student</label>
                        <select name="student_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg" required>
                            <option value="">Select Student</option>
                            @foreach($unassignedStudents as $student)
                                <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->registration_id }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Grade</label>
                        <select name="grade_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg" required>
                            <option value="">Select Grade</option>
                            @foreach($grades as $grade)
                                <option value="{{ $grade->id }}">Grade {{ $grade->level }}{{ $grade->section }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subjects</label>
                        <select name="subjects[]" id="subject_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <option value="">Select a subject</option>
            @foreach($allSubjects as $subject)
                 <option value="{{ $subject->id }}">{{ $subject->name }}</option>
            @endforeach
        </select>
         <p class="text-xs text-gray-500 mt-1">Hold Ctrl/Cmd to select multiple subjects</p>
                    </div>
                </div>
                <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-all duration-200 font-semibold flex items-center gap-2 shadow-md hover:shadow-lg">
                    <i class="fas fa-user-plus"></i>
                    Assign Student
                </button>
            </form>
        </div>

        <!-- Current Assignments Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden border border-gray-200">
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-list-alt text-blue-600"></i>
                    Current Grade Assignments
                </h2>
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
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-gray-900">Grade {{ $grade->level }}{{ $grade->section }}</div>
                                <div class="text-xs text-gray-500">{{ $grade->students->count() }} students, {{ $grade->teachers->count() }} teachers</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($grade->teachers->count() > 0)
                                    <div class="space-y-3">
                                        @foreach($grade->teachers as $teacher)
                                        <div class="bg-blue-50 rounded-lg p-3 border border-blue-200">
                                            <div class="flex items-start justify-between">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-2 mb-2">
                                                        <i class="fas fa-chalkboard-teacher text-blue-600 text-sm"></i>
                                                        <span class="font-medium text-gray-900">{{ $teacher->name }}</span>
                                                    </div>
                                                    <div class="text-xs text-gray-600 mb-2">ID: {{ $teacher->registration_id }}</div>
                                                    @php
                                                        $subjects = json_decode($teacher->pivot->subjects, true) ?? [];
                                                    @endphp
                                                   @if(!empty($teacher->subjectNames))
                                                    <div class="mt-2">
                                             <span class="text-xs font-medium text-gray-700">Subjects:</span>
                                           <div class="flex flex-wrap gap-1 mt-1">
                                            @foreach($teacher->subjectNames as $subjectName)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                         {{ $subjectName }}
                                        </span>
                                         @endforeach
                                      </div>
                                        </div>
                                          @endif
                                                </div>
                                                <form method="POST" action="{{ route('admin.unassign-teacher', ['assignment' => $teacher->pivot->id]) }}" class="unassign-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" 
                                                            onclick="confirmUnassignTeacher(this)"
                                                            class="p-2 text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition-all duration-200"
                                                            title="Remove Assignment">
                                                        <i class="fas fa-user-minus text-sm"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-user-slash text-gray-300 text-2xl mb-2"></i>
                                        <p class="text-gray-400 text-sm">No teachers assigned</p>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($grade->students->count() > 0)
                                    <div class="space-y-2">
                                        @foreach($grade->students as $student)
                                        <div class="flex items-center justify-between bg-green-50 rounded-lg p-3 border border-green-200">
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-user-graduate text-green-600 text-sm"></i>
                                                <div>
                                                    <div class="font-medium text-gray-900">{{ $student->name }}</div>
                                                    <div class="text-xs text-gray-600">ID: {{ $student->registration_id }}</div>
                                                </div>
                                            </div>
                                            <form method="POST" action="{{ route('admin.unassign-student', $student) }}" class="unassign-form">
                                                @csrf
                                                <button type="button" 
                                                        onclick="confirmUnassignStudent(this)"
                                                        class="p-2 text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 rounded-lg transition-all duration-200"
                                                        title="Unassign Student">
                                                    <i class="fas fa-user-minus text-sm"></i>
                                                </button>
                                            </form>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <i class="fas fa-users-slash text-gray-300 text-2xl mb-2"></i>
                                        <p class="text-gray-400 text-sm">No students assigned</p>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
             <button type="button" onclick="openManageSubjectsModal()" 
            class="mt-6 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-all duration-200 font-medium flex items-center gap-2">
        <i class="fas fa-plus"></i>
        Manage Subjects
    </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-400">
                                    <i class="fas fa-inbox text-4xl mb-3"></i>
                                    <p class="text-lg font-medium">No grade assignments yet</p>
                                    <p class="text-sm">Start by assigning teachers and students to grades</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Manage Subjects Modal -->
<div id="manageSubjectsModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
            <div class="bg-indigo-600 text-white p-4 rounded-t-lg flex justify-between items-center">
                <h3 class="text-xl font-semibold">Manage Subjects</h3>
                <button onclick="closeManageSubjectsModal()" class="text-white hover:bg-red-500 hover:scale-110 transition-all duration-200 p-2 rounded-full bg-red-400">&times;</button>
            </div>
            
            <!-- Add New Subject Form -->
            <div class="p-6 border-b border-gray-200">
                <h4 class="text-lg font-semibold mb-4">Add New Subject</h4>
                <form id="addSubjectForm" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Subject Name *</label>
                            <input type="text" name="name" id="newSubjectName" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                   required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Subject Code</label>
                            <input type="text" name="code" id="newSubjectCode" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="newSubjectDescription" 
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                  rows="2"></textarea>
                    </div>
                    <button type="submit" 
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-all duration-200 font-semibold flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        Add Subject
                    </button>
                </form>
            </div>

            <!-- Subjects List -->
            <div class="p-6 max-h-96 overflow-y-auto">
                <h4 class="text-lg font-semibold mb-4">Existing Subjects</h4>
                <div id="subjectsList" class="space-y-3">
                    <!-- Subjects will be loaded here dynamically -->
                </div>
            </div>

            <div class="bg-gray-50 p-4 rounded-b-lg flex justify-end">
                <button type="button" onclick="closeManageSubjectsModal()" 
                        class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition-all duration-200 font-semibold">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Edit Subject Modal -->
<div id="editSubjectModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="bg-indigo-600 text-white p-4 rounded-t-lg flex justify-between items-center">
                <h3 class="text-xl font-semibold">Edit Subject</h3>
                <button onclick="closeModal('editSubjectModal')" class="text-white hover:bg-red-500 hover:scale-110 transition-all duration-200 p-2 rounded-full bg-red-400">&times;</button>
            </div>
            
            <form id="editSubjectForm" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" id="editSubjectId" name="id">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subject Name *</label>
                    <input type="text" name="name" id="editSubjectName" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                           required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Subject Code</label>
                    <input type="text" name="code" id="editSubjectCode" 
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="editSubjectDescription" 
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                              rows="3"></textarea>
                </div>
                
                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeModal('editSubjectModal')" 
                            class="flex-1 bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-all duration-200 font-medium">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="flex-1 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-all duration-200 font-medium flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i>
                        Update Subject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Unassign Confirmation Modal -->
<div id="unassignConfirmModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden transition-opacity duration-300">
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full transform transition-all duration-300 scale-95 opacity-0" id="unassignConfirmModalContent">
            <div class="bg-indigo-600 text-white p-4 rounded-t-lg flex justify-between items-center">
                <h3 class="text-xl font-semibold">Confirm Unassignment</h3>
                <button onclick="closeUnassignModal()" class="text-white hover:bg-red-500 hover:scale-110 transition-all duration-200 p-2 rounded-full bg-red-400">&times;</button>
            </div>
            <div class="p-6">
                <p class="text-gray-700 mb-4" id="unassignMessage">Are you sure you want to remove this teacher from this grade?</p>
                <p class="text-red-600 flex items-center gap-2 text-sm">
                    <i class="fas fa-exclamation-triangle"></i>
                    This action cannot be undone.
                </p>
            </div>
            <div class="bg-gray-50 p-4 rounded-b-lg flex justify-end space-x-3">
                <button type="button" class="bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition-all duration-200 font-semibold flex items-center gap-2" onclick="closeUnassignModal()">
                    <i class="fas fa-times"></i>
                    Cancel
                </button>
                <form id="unassignForm" method="POST">
                    <!-- Form content will be dynamically inserted here -->
                    <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-all duration-200 font-semibold flex items-center gap-2">
                        <i class="fas fa-user-minus"></i>
                        Confirm Unassign
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// ========== NOTIFICATION SYSTEM ==========
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    
    const styles = {
        success: 'bg-gradient-to-r from-green-500 to-green-600 border-l-4 border-green-700',
        error: 'bg-gradient-to-r from-red-500 to-red-600 border-l-4 border-red-700',
        warning: 'bg-gradient-to-r from-yellow-500 to-yellow-600 border-l-4 border-yellow-700',
        info: 'bg-gradient-to-r from-blue-500 to-blue-600 border-l-4 border-blue-700'
    };

    const icons = {
        success: 'fa-check-circle',
        error: 'fa-exclamation-triangle',
        warning: 'fa-exclamation-circle',
        info: 'fa-info-circle'
    };

    notification.className = `fixed top-4 right-4 z-50 transform translate-x-full opacity-0 transition-all duration-500 ease-in-out ${styles[type]} text-white p-4 rounded-xl shadow-2xl max-w-sm min-w-80 backdrop-blur-sm border-l-4`;
    
    notification.innerHTML = `
        <div class="flex items-center gap-3">
            <i class="fas ${icons[type]} text-xl flex-shrink-0"></i>
            <span class="flex-1 text-sm font-medium">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" 
                    class="flex-shrink-0 p-1 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors duration-200">
                <i class="fas fa-times text-sm"></i>
            </button>
        </div>
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.classList.remove('translate-x-full', 'opacity-0');
        notification.classList.add('translate-x-0', 'opacity-100');
    }, 10);

    setTimeout(() => {
        if (notification.parentElement) {
            notification.classList.remove('translate-x-0', 'opacity-100');
            notification.classList.add('translate-x-full', 'opacity-0');
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 500);
        }
    }, 7000);
}

function showSuccessMessage(message) {
    showNotification(message, 'success');
}

function showErrorMessage(message) {
    showNotification(message, 'error');
}

function showWarningMessage(message) {
    showNotification(message, 'warning');
}

function showInfoMessage(message) {
    showNotification(message, 'info');
}

// ========== MODAL FUNCTIONS ==========
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    const modalContent = document.getElementById(modalId + 'Content');
    
    // Add null checks to prevent errors
    if (!modal) {
        console.error('Modal not found:', modalId);
        return;
    }
    
    modal.classList.remove('hidden');
    
    if (modalContent) {
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);
    }
    
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    const modalContent = document.getElementById(modalId + 'Content');
    
    // Add null checks to prevent errors
    if (!modal) {
        console.error('Modal not found:', modalId);
        return;
    }
    
    if (modalContent) {
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
    }
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }, 300);
}

// ========== UNASSIGN CONFIRMATION MODAL ==========
function confirmUnassignTeacher(button) {
    try {
        const form = button.closest('form');
        
        if (!form) {
            return;
        }
        
        let teacherName = 'this teacher';
        
        const teacherCard = button.closest('.bg-blue-50');
        
        if (teacherCard) {
            const nameElement = teacherCard.querySelector('.font-medium');
            if (nameElement && nameElement.textContent) {
                teacherName = nameElement.textContent.trim();
            }
        }
        
        document.getElementById('unassignMessage').textContent = `Are you sure you want to remove ${teacherName} from this grade?`;
        document.getElementById('unassignForm').action = form.action;
        
        const formContent = `
            <input type="hidden" name="_token" value="${form.querySelector('input[name="_token"]').value}">
            ${form.querySelector('input[name="_method"]') ? '<input type="hidden" name="_method" value="DELETE">' : ''}
            <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-all duration-200 font-semibold flex items-center gap-2">
                <i class="fas fa-user-minus"></i>
                Confirm Unassign
            </button>
        `;
        
        document.getElementById('unassignForm').innerHTML = formContent;
        
        openModal('unassignConfirmModal');
        
    } catch (error) {
        showErrorMessage('Failed to open confirmation dialog');
    }
}

function confirmUnassignStudent(button) {
    try {
        const form = button.closest('form');
        
        if (!form) {
            return;
        }
        
        let studentName = 'this student';
        
        const studentCard = button.closest('.bg-green-50');
        
        if (studentCard) {
            const nameElement = studentCard.querySelector('.font-medium');
            if (nameElement && nameElement.textContent) {
                studentName = nameElement.textContent.trim();
            }
        }
        
        document.getElementById('unassignMessage').textContent = `Are you sure you want to unassign ${studentName} from this grade?`;
        document.getElementById('unassignForm').action = form.action;
        
        const formContent = `
            <input type="hidden" name="_token" value="${form.querySelector('input[name="_token"]').value}">
            ${form.querySelector('input[name="_method"]') ? '<input type="hidden" name="_method" value="DELETE">' : ''}
            <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-all duration-200 font-semibold flex items-center gap-2">
                <i class="fas fa-user-minus"></i>
                Confirm Unassign
            </button>
        `;
        
        document.getElementById('unassignForm').innerHTML = formContent;
        
        openModal('unassignConfirmModal');
        
    } catch (error) {
        showErrorMessage('Failed to open confirmation dialog');
    }
}

function closeUnassignModal() {
    closeModal('unassignConfirmModal');
}

// ========== FORM SUBMISSIONS ==========
document.addEventListener('DOMContentLoaded', function() {
    
    // Assign Teacher Form
    const assignTeacherForm = document.getElementById('assignTeacherForm');
    if (assignTeacherForm) {
        assignTeacherForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Assigning...';
            submitButton.disabled = true;

            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => {
                if (response.redirected) {
                    return { success: true };
                }
                return response.text().then(text => {
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        return { success: true };
                    }
                });
            })
            .then(data => {
                if (data.success) {
                    showSuccessMessage('Teacher assigned successfully!');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    throw new Error(data.message || 'Failed to assign teacher');
                }
            })
            .catch(error => {
                showErrorMessage('Failed to assign teacher: ' + error.message);
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            });
        });
    }

    // Assign Student Form
    const assignStudentForm = document.getElementById('assignStudentForm');
    if (assignStudentForm) {
        assignStudentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Assigning...';
            submitButton.disabled = true;

            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => {
                if (response.redirected) {
                    return { success: true };
                }
                return response.text().then(text => {
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        return { success: true };
                    }
                });
            })
            .then(data => {
                if (data.success) {
                    showSuccessMessage('Student assigned successfully!');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    throw new Error(data.message || 'Failed to assign student');
                }
            })
            .catch(error => {
                showErrorMessage('Failed to assign student: ' + error.message);
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            });
        });
    }
    
    // Unassign Form Event Listener
    const unassignForm = document.getElementById('unassignForm');
    
    if (unassignForm) {
        unassignForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitButton = this.querySelector('button[type="submit"]');
            
            if (!submitButton) {
                return;
            }
            
            const originalText = submitButton.innerHTML;
            
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Processing...';
            submitButton.disabled = true;

            const formData = new FormData(this);
            
            const method = formData.get('_method') || 'POST';
            const actualMethod = method === 'DELETE' ? 'DELETE' : 'POST';
            
            const finalFormData = new FormData();
            finalFormData.append('_token', formData.get('_token'));

            fetch(this.action, {
                method: actualMethod,
                body: finalFormData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => {
                if (response.redirected) {
                    return { success: true };
                }
                
                return response.text().then(text => {
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        return { success: true };
                    }
                });
            })
            .then(data => {
                if (data.success) {
                    closeModal('unassignConfirmModal');
                    showSuccessMessage('Unassigned successfully!');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    throw new Error(data.message || 'Failed to unassign');
                }
            })
            .catch(error => {
                showErrorMessage('Failed to unassign: ' + error.message);
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            });
        });
    }
});


// Modal functions
function openManageSubjectsModal() {
    openModal('manageSubjectsModal');
    loadSubjects();
}

function closeManageSubjectsModal() {
    closeModal('manageSubjectsModal');
}

// Load subjects from database
function loadSubjects() {
    fetch('/subjects')
        .then(response => response.json())
        .then(subjects => {
            const subjectsList = document.getElementById('subjectsList');
            subjectsList.innerHTML = subjects.map(subject => `
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <div>
                        <h5 class="font-semibold text-gray-800">${subject.name}</h5>
                        ${subject.code ? `<p class="text-sm text-gray-600">Code: ${subject.code}</p>` : ''}
                        ${subject.description ? `<p class="text-sm text-gray-500 mt-1">${subject.description}</p>` : ''}
                    </div>
                    <button onclick="editSubject(${subject.id})" 
                            class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition-all duration-200 text-sm">
                        <i class="fas fa-edit"></i>
                    </button>
                </div>
            `).join('');
        })
        .catch(error => {
            console.error('Error loading subjects:', error);
            showErrorMessage('Failed to load subjects');
        });
}

// Add new subject
document.getElementById('addSubjectForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Adding...';
    submitButton.disabled = true;

    fetch('/subjects', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessMessage(data.message);
            this.reset();
            loadSubjects(); // Refresh the list
            updateSubjectDropdown(); // Update the assignment dropdown
        } else {
            throw new Error(data.message || 'Failed to add subject');
        }
    })
    .catch(error => {
        showErrorMessage('Failed to add subject: ' + error.message);
    })
    .finally(() => {
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
    });
});

// Update subject dropdown in assignment form
function updateSubjectDropdown() {
    fetch('/subjects')
        .then(response => response.json())
        .then(subjects => {
            const subjectSelect = document.getElementById('subject_id');
            if (subjectSelect) {
                // Store current selection
                const currentValue = subjectSelect.value;
                
                // Clear and repopulate options
                subjectSelect.innerHTML = '<option value="">Select a subject</option>';
                subjects.forEach(subject => {
                    const option = document.createElement('option');
                    option.value = subject.id;
                    option.textContent = subject.name;
                    subjectSelect.appendChild(option);
                });
                
                // Restore selection if still valid
                if (currentValue && subjects.find(s => s.id == currentValue)) {
                    subjectSelect.value = currentValue;
                }
            }
        });
}





// Edit subject function - complete implementation
function editSubject(subjectId) {
    // First, fetch the subject data
    fetch(`/subjects/${subjectId}`)
        .then(response => response.json())
        .then(subject => {
            // Populate the edit form with current data
            document.getElementById('editSubjectId').value = subject.id;
            document.getElementById('editSubjectName').value = subject.name;
            document.getElementById('editSubjectCode').value = subject.code || '';
            document.getElementById('editSubjectDescription').value = subject.description || '';
            
            // Show the edit modal
            openModal('editSubjectModal');
        })
        .catch(error => {
            console.error('Error fetching subject:', error);
            showErrorMessage('Failed to load subject data');
        });
}

// Update subject
document.getElementById('editSubjectForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const subjectId = document.getElementById('editSubjectId').value;
    const formData = new FormData(this);
    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Updating...';
    submitButton.disabled = true;

    fetch(`/subjects/${subjectId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'X-Requested-With': 'XMLHttpRequest',
            'X-HTTP-Method-Override': 'PUT' // For PUT method
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessMessage(data.message);
            closeModal('editSubjectModal');
            loadSubjects(); // Refresh the list
            updateSubjectDropdown(); // Update assignment dropdown
        } else {
            throw new Error(data.message || 'Failed to update subject');
        }
    })
    .catch(error => {
        showErrorMessage('Failed to update subject: ' + error.message);
    })
    .finally(() => {
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
    });
});


</script>
@endsection