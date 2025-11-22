@extends('layouts.admin')

@section('title', 'Manage Students - WKG')

@section('content')
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 p-6 bg-white rounded-xl shadow-sm mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Student Management</h1>
        <button onclick="openCreateStudentModal()" class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
            <i class="fas fa-plus-circle"></i>
            Add New Student
        </button>
    </div>

    <!-- Student Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Total Students Card -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl p-6 shadow-lg transform transition-all duration-300 hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <div class="bg-white/20 p-3 rounded-full inline-flex items-center justify-center mb-3">
                        <i class="fas fa-users text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-1">Total Students</h3>
                    <p class="text-3xl font-bold">{{ $totalStudents }}</p>
                </div>
                <div class="text-blue-100">
                    <i class="fas fa-graduation-cap text-4xl opacity-70"></i>
                </div>
            </div>
        </div>

        <!-- Male Students Card -->
        <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-xl p-6 shadow-lg transform transition-all duration-300 hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <div class="bg-white/20 p-3 rounded-full inline-flex items-center justify-center mb-3">
                        <i class="fas fa-male text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-1">Male Students</h3>
                    <p class="text-3xl font-bold">{{ $maleStudents }}</p>
                </div>
                <div class="text-indigo-100">
                    <i class="fas fa-user text-4xl opacity-70"></i>
                </div>
            </div>
        </div>

        <!-- Female Students Card -->
        <div class="bg-gradient-to-r from-pink-500 to-pink-600 text-white rounded-xl p-6 shadow-lg transform transition-all duration-300 hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <div class="bg-white/20 p-3 rounded-full inline-flex items-center justify-center mb-3">
                        <i class="fas fa-female text-xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-1">Female Students</h3>
                    <p class="text-3xl font-bold">{{ $femaleStudents }}</p>
                </div>
                <div class="text-pink-100">
                    <i class="fas fa-user text-4xl opacity-70"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="mb-6 flex flex-col sm:flex-row gap-3 bg-white p-4 rounded-xl shadow-sm">
        <div class="relative flex-grow">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
            </div>
            <input type="text" id="searchInput" class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg w-full focus:ring-2 focus:ring-blue-200 focus:border-blue-500 transition-colors" placeholder="Search students by name, ID, or class...">
        </div>
        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
            <i class="fas fa-filter"></i>
            Filters
        </button>
    </div>

    <!-- Search Results Info -->
    <div id="searchResultsInfo"></div>

    <!-- Loading Spinner -->
    <div id="loadingSpinner" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-xl p-8 shadow-2xl">
            <div class="flex flex-col items-center">
                <div class="relative">
                    <i class="fas fa-spinner fa-spin text-4xl text-blue-500 mb-2"></i>
                    <div class="absolute inset-0 bg-blue-500 rounded-full animate-ping opacity-20"></div>
                </div>
                <p class="mt-2 text-gray-600 font-medium">Searching students...</p>
                <p class="text-sm text-gray-400">Please wait while we find the best matches</p>
            </div>
        </div>
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Age</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gender</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parent Phone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admission Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="studentsTable">
                    @foreach($students as $student)
                    <tr class="student-row hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $student->student_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $initials = implode('', array_map(function($word) {
                                    return strtoupper(substr(trim($word), 0, 1));
                                }, array_slice(explode(' ', $student->name), 0, 2)));
                                
                                $colors = ['bg-blue-100', 'bg-green-100', 'bg-yellow-100', 'bg-red-100', 'bg-purple-100', 'bg-pink-100'];
                                $colorClass = $colors[array_rand($colors)];
                            @endphp
                            
                            @if($student->image)
                                <img src="{{ asset('storage/'.$student->image) }}" 
                                     class="h-10 w-10 rounded-full object-cover border border-gray-200"
                                     alt="{{ $student->name }} avatar">
                            @else
                                <div class="h-10 w-10 rounded-full {{ $colorClass }} flex items-center justify-center font-semibold text-gray-600">
                                    {{ $initials ?: '?' }}
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $student->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $student->age }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $student->gender }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $student->parent_phone }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $student->class_applying_for }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $student->date_of_admission->format('M d, Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button onclick="openViewStudentModal('{{ $student->id }}')" class="text-blue-600 hover:text-blue-900 transition-colors">
                                    <i class=" fas fa-eye"></i>
                                </button>
                                <button onclick="openEditStudentModal('{{ $student->id }}')" class="text-indigo-600 hover:text-indigo-900 transition-colors">
                                 <i class="fas fa-edit"></i>
                                 </button>
                                <button onclick="openDeleteModal('{{ $student->id }}', '{{ $student->name }}')" class="text-red-600 hover:text-red-900 transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $students->links() }}
        </div>
    </div>

    <!-- Add Student Modal -->
    <div id="createStudentModal" class="fixed inset-0 bg-blue-900 bg-opacity-60 backdrop-blur-sm overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative top-8 mx-auto p-2 w-full max-w-4xl">
            <div class="bg-white rounded-2xl shadow-2xl border border-blue-100 transform transition-all duration-300">
                <!-- Modal Header -->
                <div class="flex items-center justify-between p-6 bg-gradient-to-r from-blue-600 to-blue-700 rounded-t-2xl">
                    <div class="text-white">
                        <h3 class="text-2xl font-bold">Register New Student</h3>
                        <p class="text-blue-100 mt-1">Complete student registration form</p>
                    </div>
                    <button onclick="closeCreateStudentModal()" class="text-white hover:bg-red-500 hover:scale-110 transition-all duration-200 p-2 rounded-full bg-red-400">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6 max-h-[80vh] overflow-y-auto">
                    <form action="{{ route('admin.students.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="createStudentForm">
                        @csrf
                        
                        <!-- Personal Information Section -->
                        <div class="bg-blue-50 p-4 rounded-xl border border-blue-200">
                            <h2 class="text-lg font-semibold text-blue-800 mb-4 flex items-center">
                                <i class="fas fa-user-circle mr-2"></i>
                                Personal Information
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                                    <input type="text" name="name" value="{{ old('name') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Age *</label>
                                    <input type="number" name="age" value="{{ old('age') }}" required min="5" max="25"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Gender *</label>
                                    <select name="gender" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <option value="">Select Gender</option>
                                        <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Parent Phone *</label>
                                    <input type="text" name="parent_phone" value="{{ old('parent_phone') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Academic Information Section -->
                        <div class="bg-green-50 p-4 rounded-xl border border-green-200">
                            <h2 class="text-lg font-semibold text-green-800 mb-4 flex items-center">
                                <i class="fas fa-graduation-cap mr-2"></i>
                                Academic Information
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Class Applying For *</label>
                                    <select name="class_applying_for" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                        <option value="">Select Class</option>
                                        <optgroup label="Nursery">
                                            <option value="Nursery 1">Nursery 1</option>
                                            <option value="Nursery 2">Nursery 2</option>
                                            <option value="Nursery 3">Nursery 3</option>
                                        </optgroup>
                                        <optgroup label="Primary">
                                            <option value="Grade 1">Grade 1</option>
                                            <option value="Grade 2">Grade 2</option>
                                            <option value="Grade 3">Grade 3</option>
                                            <option value="Grade 4">Grade 4</option>
                                            <option value="Grade 5">Grade 5</option>
                                            <option value="Grade 6">Grade 6</option>
                                        </optgroup>
                                        <optgroup label="Secondary">
                                            <option value="Grade 7">Grade 7</option>
                                            <option value="Grade 8">Grade 8</option>
                                            <option value="Grade 9">Grade 9</option>
                                            <option value="Grade 10">Grade 10</option>
                                            <option value="Grade 11">Grade 11</option>
                                            <option value="Grade 12">Grade 12</option>
                                        </optgroup>
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Date of Admission *</label>
                                    <input type="date" name="date_of_admission" value="{{ old('date_of_admission') }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                            </div>
                        </div>

                        <!-- Documents Section -->
                        <div class="bg-purple-50 p-4 rounded-xl border border-purple-200">
                            <h2 class="text-lg font-semibold text-purple-800 mb-4 flex items-center">
                                <i class="fas fa-file-alt mr-2"></i>
                                Documents
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Student Image</label>
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition-colors cursor-pointer">
                                        <input type="file" id="image" name="image" class="hidden" onchange="displayFileName(this, 'image')">
                                        <label for="image" class="cursor-pointer">
                                            <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-2"></i>
                                            <p class="text-gray-600 font-medium">Click to upload image</p>
                                            <p class="text-gray-400 text-sm mt-1">JPG, PNG, GIF (Max: 2MB)</p>
                                        </label>
                                    </div>
                                    <div id="imageFileName" class="hidden mt-2 p-2 bg-green-50 border border-green-200 rounded text-green-700 text-sm"></div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Transcript (PDF/DOC)</label>
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition-colors cursor-pointer">
                                        <input type="file" id="transcript" name="transcript" class="hidden" onchange="displayFileName(this, 'transcript')">
                                        <label for="transcript" class="cursor-pointer">
                                            <i class="fas fa-file-pdf text-2xl text-red-400 mb-2"></i>
                                            <p class="text-gray-600 font-medium">Click to upload transcript</p>
                                            <p class="text-gray-400 text-sm mt-1">PDF, DOC, DOCX (Max: 5MB)</p>
                                        </label>
                                    </div>
                                    <div id="transcriptFileName" class="hidden mt-2 p-2 bg-green-50 border border-green-200 rounded text-green-700 text-sm"></div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Recommendation Letter (PDF/DOC)</label>
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition-colors cursor-pointer">
                                        <input type="file" id="recommendation_letter" name="recommendation_letter" class="hidden" onchange="displayFileName(this, 'recommendation')">
                                        <label for="recommendation_letter" class="cursor-pointer">
                                            <i class="fas fa-file-alt text-2xl text-blue-400 mb-2"></i>
                                            <p class="text-gray-600 font-medium">Click to upload letter</p>
                                            <p class="text-gray-400 text-sm mt-1">PDF, DOC, DOCX (Max: 5MB)</p>
                                        </label>
                                    </div>
                                    <div id="recommendationFileName" class="hidden mt-2 p-2 bg-green-50 border border-green-200 rounded text-green-700 text-sm"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
                            <button type="button" onclick="closeCreateStudentModal()" 
                                    class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium flex items-center gap-2">
                                <i class="fas fa-user-plus"></i>
                                Register Student
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteStudentModal" class="fixed inset-0 bg-blue-900 bg-opacity-60 backdrop-blur-sm overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative top-20 mx-auto p-2 w-full max-w-md">
            <div class="bg-white rounded-2xl shadow-2xl border border-red-100">
                <!-- Modal Header -->
                <div class="bg-indigo-600 text-white p-4 rounded-t-lg flex justify-between items-center">
                    <div class="text-white">
                        <h3 class="text-xl font-semibold">Confirm Delete</h3>
                    </div>
                    <button onclick="closeDeleteModal()"  class="text-white hover:bg-red-500 hover:scale-110 transition-all duration-200 p-2 rounded-full bg-red-400">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <div class="p-6">
                    <p class="text-gray-700 mb-4">Are you sure you want to delete this student?</p>
                    <p id="deleteStudentDetails" class="font-bold text-gray-800 my-3 p-3 bg-gray-50 rounded-lg border border-gray-200"></p>
                    <p class="text-red-600 flex items-center">
                        <i class="fas fa-exclamation-triangle mr-2"></i>This action cannot be undone.
                    </p>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 p-4 rounded-b-2xl border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="closeDeleteModal()" 
                            class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        Cancel
                    </button>
                    <form id="deleteStudentForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium flex items-center gap-2">
                            <i class="fas fa-trash"></i>
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Edit Student Modal -->
<div id="editStudentModal" class="fixed inset-0 bg-blue-900 bg-opacity-60 backdrop-blur-sm overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-8 mx-auto p-2 w-full max-w-4xl">
        <div class="bg-white rounded-2xl shadow-2xl border border-blue-100 transform transition-all duration-300">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-t-2xl">
                <div class="text-white">
                    <h3 class="text-2xl font-bold">Edit Student</h3>
                    <p class="text-indigo-100 mt-1">Update student information</p>
                </div>
                <button onclick="closeEditStudentModal()" class="text-white hover:bg-red-500 hover:scale-110 transition-all duration-200 p-2 rounded-full bg-red-400">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 max-h-[80vh] overflow-y-auto">
                <form id="editStudentForm" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Personal Information Section -->
                    <div class="bg-blue-50 p-4 rounded-xl border border-blue-200">
                        <h2 class="text-lg font-semibold text-blue-800 mb-4 flex items-center">
                            <i class="fas fa-user-circle mr-2"></i>
                            Personal Information
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name *</label>
                                <input type="text" name="name" id="edit_name" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Age *</label>
                                <input type="number" name="age" id="edit_age" required min="5" max="25"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Gender *</label>
                                <select name="gender" id="edit_gender" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Parent Phone *</label>
                                <input type="text" name="parent_phone" id="edit_parent_phone" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Academic Information Section -->
                    <div class="bg-green-50 p-4 rounded-xl border border-green-200">
                        <h2 class="text-lg font-semibold text-green-800 mb-4 flex items-center">
                            <i class="fas fa-graduation-cap mr-2"></i>
                            Academic Information
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Class Applying For *</label>
                                <select name="class_applying_for" id="edit_class_applying_for" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                    <option value="">Select Class</option>
                                    <optgroup label="Nursery">
                                        <option value="Nursery 1">Nursery 1</option>
                                        <option value="Nursery 2">Nursery 2</option>
                                        <option value="Nursery 3">Nursery 3</option>
                                    </optgroup>
                                    <optgroup label="Primary">
                                        <option value="Grade 1">Grade 1</option>
                                        <option value="Grade 2">Grade 2</option>
                                        <option value="Grade 3">Grade 3</option>
                                        <option value="Grade 4">Grade 4</option>
                                        <option value="Grade 5">Grade 5</option>
                                        <option value="Grade 6">Grade 6</option>
                                    </optgroup>
                                    <optgroup label="Secondary">
                                        <option value="Grade 7">Grade 7</option>
                                        <option value="Grade 8">Grade 8</option>
                                        <option value="Grade 9">Grade 9</option>
                                        <option value="Grade 10">Grade 10</option>
                                        <option value="Grade 11">Grade 11</option>
                                        <option value="Grade 12">Grade 12</option>
                                    </optgroup>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Date of Admission *</label>
                                <input type="date" name="date_of_admission" id="edit_date_of_admission" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            </div>
                        </div>
                    </div>

                    <!-- Documents Section -->
                    <div class="bg-purple-50 p-4 rounded-xl border border-purple-200">
                        <h2 class="text-lg font-semibold text-purple-800 mb-4 flex items-center">
                            <i class="fas fa-file-alt mr-2"></i>
                            Documents
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Student Image</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition-colors cursor-pointer">
                                    <input type="file" id="edit_image" name="image" class="hidden" onchange="displayEditImagePreview(this)">
                                    <label for="edit_image" class="cursor-pointer">
                                        <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-2"></i>
                                        <p class="text-gray-600 font-medium">Click to upload image</p>
                                        <p class="text-gray-400 text-sm mt-1">JPG, PNG, GIF (Max: 2MB)</p>
                                    </label>
                                </div>
                                <div id="currentImageContainer" class="mt-3 hidden">
                                    <p id="currentImageText" class="text-sm text-gray-600 mb-1"></p>
                                    <img id="edit_image_preview" class="preview-image h-24 w-24 object-cover rounded-lg border">
                                </div>
                            </div>
                            
                            <!-- Transcript -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Transcript (PDF/DOC)</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition-colors cursor-pointer">
                                    <input type="file" id="edit_transcript" name="transcript" class="hidden" onchange="displayEditFileName(this, 'edit_transcript')">
                                    <label for="edit_transcript" class="cursor-pointer">
                                        <i class="fas fa-file-pdf text-2xl text-red-400 mb-2"></i>
                                        <p class="text-gray-600 font-medium">Click to upload transcript</p>
                                        <p class="text-gray-400 text-sm mt-1">PDF, DOC, DOCX (Max: 5MB)</p>
                                    </label>
                                </div>
                                <div id="edit_transcriptFileName" class="hidden mt-2 p-2 bg-green-50 border border-green-200 rounded text-green-700 text-sm"></div>
                                <div id="currentTranscriptContainer" class="mt-3 hidden">
                                    <a href="#" target="_blank" class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-800 text-sm">
                                        <i class="fas fa-eye"></i>
                                        <span id="currentTranscriptText">View Current Transcript</span>
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Recommendation Letter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Recommendation Letter (PDF/DOC)</label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-blue-400 transition-colors cursor-pointer">
                                    <input type="file" id="edit_recommendation_letter" name="recommendation_letter" class="hidden" onchange="displayEditFileName(this, 'edit_recommendation')">
                                    <label for="edit_recommendation_letter" class="cursor-pointer">
                                        <i class="fas fa-file-alt text-2xl text-blue-400 mb-2"></i>
                                        <p class="text-gray-600 font-medium">Click to upload letter</p>
                                        <p class="text-gray-400 text-sm mt-1">PDF, DOC, DOCX (Max: 5MB)</p>
                                    </label>
                                </div>
                                <div id="edit_recommendationFileName" class="hidden mt-2 p-2 bg-green-50 border border-green-200 rounded text-green-700 text-sm"></div>
                                <div id="currentRecommendationContainer" class="mt-3 hidden">
                                    <a href="#" target="_blank" class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-800 text-sm">
                                        <i class="fas fa-eye"></i>
                                        <span id="currentRecommendationText">View Current Letter</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
                        <button type="button" onclick="closeEditStudentModal()" 
                                class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium flex items-center gap-2">
                            <i class="fas fa-save"></i>
                            Update Student
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- View Student Modal -->
<div id="viewStudentModal" class="fixed inset-0 bg-blue-900 bg-opacity-60 backdrop-blur-sm overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-8 mx-auto p-2 w-full max-w-6xl">
        <div class="bg-white rounded-2xl shadow-2xl border border-blue-100 transform transition-all duration-300">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 bg-gradient-to-r from-blue-600 to-blue-700 rounded-t-2xl">
                <div class="text-white">
                    <h3 class="text-2xl font-bold" id="viewStudentTitle">Student Details</h3>
                    <p class="text-blue-100 mt-1" id="viewStudentSubtitle">Complete student information</p>
                </div>
                <button onclick="closeViewStudentModal()" class="text-white hover:bg-red-500 hover:scale-110 transition-all duration-200 p-2 rounded-full bg-red-400">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 max-h-[80vh] overflow-y-auto">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Student Information</h2>
                    <div class="flex space-x-2 mt-4 sm:mt-0">
                        <button id="viewEditButton" class="btn-primary">
                            <i class="fas fa-edit mr-1"></i> Edit
                        </button>
                        <button onclick="closeViewStudentModal()" class="btn-secondary">
                            <i class="fas fa-list mr-1"></i> Back to List
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-1">
                        <div class="mb-6">
                            <div id="viewStudentImage" class="w-full h-64 rounded-lg shadow-md flex items-center justify-center">
                                <!-- Image will be loaded here -->
                            </div>
                        </div>

                        
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Documents</h3>
                            <div id="viewStudentDocuments" class="space-y-3">
                               
                            </div>
                        </div>
                    </div>
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                            <table class="w-full">
                                <tbody id="viewStudentDetails">
                                   
                                </tbody>
                            </table>
                        </div>

                        <!-- Additional Actions -->
                        <div class="mt-6 flex flex-wrap gap-3">
                            <button id="viewEditButtonBottom" class="btn-primary">
                                <i class="fas fa-edit mr-1"></i> Edit Student
                            </button>
                            <button id="viewDeleteButton" class="btn-secondary text-red-600 hover:bg-red-50 hover:text-red-700">
                                <i class="fas fa-trash mr-1"></i> Delete Student
                            </button>
                            <button onclick="closeViewStudentModal()" class="btn-secondary">
                                <i class="fas fa-list mr-1"></i> View All Students
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <script>
        // ========== SEARCH FUNCTIONALITY ==========
        let searchTimeout;
        
        function performSearch() {
            const searchTerm = document.getElementById('searchInput').value.trim();
            const studentRows = document.querySelectorAll('#studentsTable .student-row');
            
            showLoadingSpinner();
            
            clearTimeout(searchTimeout);
            
            searchTimeout = setTimeout(() => {
                let visibleCount = 0;
                
                studentRows.forEach(row => {
                    const textContent = row.textContent.toLowerCase();
                    if (searchTerm === '' || textContent.includes(searchTerm)) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                // Show search results info
                showSearchResultsInfo(visibleCount, studentRows.length, searchTerm);
                
                setTimeout(() => {
                    hideLoadingSpinner();
                }, 300);
                
            }, 500);
        }

        function showSearchResultsInfo(currentCount, totalCount, searchTerm) {
            let resultsInfo = document.getElementById('searchResultsInfo');
            
            if (!resultsInfo) {
                resultsInfo = document.createElement('div');
                resultsInfo.id = 'searchResultsInfo';
                resultsInfo.className = 'mb-4';
                const studentsTable = document.querySelector('.bg-white.rounded-xl.shadow-sm');
                studentsTable.parentNode.insertBefore(resultsInfo, studentsTable);
            }
            
            if (searchTerm) {
                if (currentCount === 0) {
                    resultsInfo.innerHTML = `
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                            <i class="fas fa-search text-yellow-500 text-2xl mb-2"></i>
                            <h3 class="text-lg font-medium text-yellow-800">No students found</h3>
                            <p class="text-yellow-600 mt-1">No students match your search for "${searchTerm}"</p>
                            <p class="text-yellow-500 text-sm mt-2">Please try different search terms</p>
                            <button onclick="clearSearch()" class="mt-3 bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 transition-colors font-medium">
                                <i class="fas fa-times mr-2"></i> Clear Search
                            </button>
                        </div>
                    `;
                } else {
                    resultsInfo.innerHTML = `
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                            Showing ${currentCount} of ${totalCount} students matching "${searchTerm}"
                            <button onclick="clearSearch()" class="ml-2 text-blue-600 hover:text-blue-800 font-medium">Clear search</button>
                        </div>
                    `;
                }
            } else {
                resultsInfo.innerHTML = '';
            }
        }

        function clearSearch() {
            document.getElementById('searchInput').value = '';
            performSearch();
        }

        function showLoadingSpinner() {
            const spinner = document.getElementById('loadingSpinner');
            const studentsTable = document.getElementById('studentsTable');
            
            if (studentsTable) {
                studentsTable.querySelectorAll('.student-row').forEach(row => {
                    row.style.opacity = '0.6';
                });
            }
            
            spinner.classList.remove('hidden');
        }

        function hideLoadingSpinner() {
            const spinner = document.getElementById('loadingSpinner');
            const studentsTable = document.getElementById('studentsTable');
            
            if (studentsTable) {
                studentsTable.querySelectorAll('.student-row').forEach(row => {
                    row.style.opacity = '1';
                });
            }
            
            spinner.classList.add('hidden');
        }

        // ==========DELETE  MODAL FUNCTIONALITY ==========
        function openCreateStudentModal() {
            const modal = document.getElementById('createStudentModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeCreateStudentModal() {
            const modal = document.getElementById('createStudentModal');
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        function openDeleteModal(studentId, studentName) {
            document.getElementById('deleteStudentDetails').textContent = `"${studentName}"`;
            document.getElementById('deleteStudentForm').action = `/admin/students/${studentId}`;
            
            const modal = document.getElementById('deleteStudentModal');
            modal.classList.remove('hidden');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteStudentModal');
            modal.classList.add('hidden');
        }

        // File upload display
        function displayFileName(input, type) {
            const fileNames = {
                'image': 'imageFileName',
                'transcript': 'transcriptFileName',
                'recommendation': 'recommendationFileName'
            };
            
            const fileNameDisplay = document.getElementById(fileNames[type]);
            if (input.files.length > 0) {
                fileNameDisplay.textContent = `Selected file: ${input.files[0].name}`;
                fileNameDisplay.classList.remove('hidden');
            } else {
                fileNameDisplay.classList.add('hidden');
            }
        }

   // ========== EDIT STUDENT MODAL FUNCTIONALITY ==========
function openEditStudentModal(studentId) {
    console.log('Opening edit modal for student ID:', studentId);
    
    // Show loading state
    showInfoMessage('Loading student data...');
    
    // Get CSRF token
    const csrfToken = document.querySelector('input[name="_token"]').value;
    
    fetch(`/admin/students/${studentId}/edit?ajax=1`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Received student data:', data);
        
        if (data.success) {
            const student = data.student;
            
            // Populate form fields
            document.getElementById('edit_name').value = student.name;
            document.getElementById('edit_age').value = student.age;
            document.getElementById('edit_gender').value = student.gender;
            document.getElementById('edit_parent_phone').value = student.parent_phone;
            document.getElementById('edit_class_applying_for').value = student.class_applying_for;
            document.getElementById('edit_date_of_admission').value = student.date_of_admission;
            
            // Set form action
            document.getElementById('editStudentForm').action = `/admin/students/${studentId}`;
            
            // Handle image display
            const preview = document.getElementById('edit_image_preview');
            const currentImageContainer = document.getElementById('currentImageContainer');
            
            if (student.image && student.image_exists) {
                preview.src = `/storage/${student.image}?v=${new Date().getTime()}`;
                currentImageContainer.classList.remove('hidden');
                document.getElementById('currentImageText').textContent = 'Current student image';
            } else {
                // Show initials if no image
                const initials = getInitials(student.name);
                preview.src = "data:image/svg+xml;charset=UTF-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Crect fill='%23e5e7eb' width='100' height='100'/%3E%3Ctext fill='%236b7280' font-family='Arial' font-size='40' x='50%25' y='50%25' text-anchor='middle' dy='.3em'%3E" + initials + "%3C/text%3E%3C/svg%3E";
                currentImageContainer.classList.remove('hidden');
                document.getElementById('currentImageText').textContent = `Current display: ${initials} (initials)`;
            }
            
            // Handle transcript display
            const currentTranscriptContainer = document.getElementById('currentTranscriptContainer');
            if (student.transcript && student.transcript_exists) {
                currentTranscriptContainer.classList.remove('hidden');
                currentTranscriptContainer.querySelector('a').href = `/storage/${student.transcript}`;
            } else {
                currentTranscriptContainer.classList.add('hidden');
            }
            
            // Handle recommendation letter display
            const currentRecommendationContainer = document.getElementById('currentRecommendationContainer');
            if (student.recommendation_letter && student.recommendation_letter_exists) {
                currentRecommendationContainer.classList.remove('hidden');
                currentRecommendationContainer.querySelector('a').href = `/storage/${student.recommendation_letter}`;
            } else {
                currentRecommendationContainer.classList.add('hidden');
            }
            
            // Show modal
            const modal = document.getElementById('editStudentModal');
            modal.classList.remove('hidden');
            
            console.log('Edit student modal populated successfully');
        } else {
            throw new Error(data.message || 'Failed to load student data');
        }
    })
    .catch(error => {
        console.error('Error loading student data:', error);
        showErrorMessage('Failed to load student data: ' + error.message);
    });
}

function closeEditStudentModal() {
    const modal = document.getElementById('editStudentModal');
    if (modal) {
        modal.classList.add('hidden');
        resetEditStudentForm();
    }
}

function displayEditImagePreview(input) {
    const preview = document.getElementById('edit_image_preview');
    const currentImageContainer = document.getElementById('currentImageContainer');
    
    if (input.files && input.files[0]) {
        preview.src = URL.createObjectURL(input.files[0]);
        currentImageContainer.classList.remove('hidden');
        document.getElementById('currentImageText').textContent = 'New image selected - will replace current image';
    }
}

function displayEditFileName(input, type) {
    const fileNames = {
        'edit_transcript': 'edit_transcriptFileName',
        'edit_recommendation': 'edit_recommendationFileName'
    };
    
    const fileNameDisplay = document.getElementById(fileNames[type]);
    if (input.files.length > 0) {
        fileNameDisplay.textContent = `Selected file: ${input.files[0].name}`;
        fileNameDisplay.classList.remove('hidden');
        
        // Update the current file text to show new file will replace
        if (type === 'edit_transcript') {
            document.getElementById('currentTranscriptText').textContent = 'New file selected - will replace current transcript';
        } else if (type === 'edit_recommendation') {
            document.getElementById('currentRecommendationText').textContent = 'New file selected - will replace current letter';
        }
    } else {
        fileNameDisplay.classList.add('hidden');
    }
}

function resetEditStudentForm() {
    document.getElementById('editStudentForm').reset();
    document.getElementById('currentImageContainer').classList.add('hidden');
    document.getElementById('currentTranscriptContainer').classList.add('hidden');
    document.getElementById('currentRecommendationContainer').classList.add('hidden');
    
    // Clear file name displays
    document.getElementById('edit_transcriptFileName').classList.add('hidden');
    document.getElementById('edit_recommendationFileName').classList.add('hidden');
}

// Handle edit form submission with AJAX
document.getElementById('editStudentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    console.log('Edit student form submitted via AJAX');
    
    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    
    // Show loading state
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Updating...';
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
        // If we get a redirect (success), handle it
        if (response.redirected) {
            return { success: true };
        }
        
        // Try to parse as JSON, if fails assume success
        return response.text().then(text => {
            try {
                return JSON.parse(text);
            } catch (e) {
                // If it's not JSON but we got here, assume success
                return { success: true };
            }
        });
    })
    .then(data => {
        if (data.success) {
            closeEditStudentModal();
            showSuccessMessage('Student updated successfully!');
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            throw new Error(data.message || 'Failed to update student');
        }
    })
    .catch(error => {
        console.error('Edit student error:', error);
        showErrorMessage('Failed to update student: ' + error.message);
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
    });
});

// Helper function to get initials (reuse from your user modal)
function getInitials(name) {
    return name.split(' ').map(word => word.charAt(0).toUpperCase()).join('').substring(0, 2);
}

// ========== VIEW STUDENT MODAL FUNCTIONALITY ==========
function openViewStudentModal(studentId) {
    console.log('Opening view modal for student ID:', studentId);
    
    // Show loading state
    showInfoMessage('Loading student details...');
    
    // Get CSRF token
    const csrfToken = document.querySelector('input[name="_token"]').value;
    
    fetch(`/admin/students/${studentId}?ajax=1`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Received student details:', data);
        
        if (data.success) {
            const student = data.student;
            
            // Update modal title
            document.getElementById('viewStudentTitle').textContent = `Student Details: ${student.student_id}`;
            document.getElementById('viewStudentSubtitle').textContent = student.name;
            
            // Set up edit and delete buttons
            document.getElementById('viewEditButton').onclick = () => {
                closeViewStudentModal();
                setTimeout(() => openEditStudentModal(studentId), 300);
            };
            document.getElementById('viewEditButtonBottom').onclick = () => {
                closeViewStudentModal();
                setTimeout(() => openEditStudentModal(studentId), 300);
            };
            document.getElementById('viewDeleteButton').onclick = () => {
                closeViewStudentModal();
                setTimeout(() => openDeleteModal(studentId, student.name), 300);
            };
            
        
            loadStudentImage(student);
            
            loadStudentDocuments(student);
            
            loadStudentDetails(student);
            
            // Show modal
            const modal = document.getElementById('viewStudentModal');
            modal.classList.remove('hidden');
            
            console.log('View student modal populated successfully');
        } else {
            throw new Error(data.message || 'Failed to load student details');
        }
    })
    .catch(error => {
        console.error('Error loading student details:', error);
        showErrorMessage('Failed to load student details: ' + error.message);
    });
}

function closeViewStudentModal() {
    const modal = document.getElementById('viewStudentModal');
    if (modal) {
        modal.classList.add('hidden');
    }
}

function loadStudentImage(student) {
    const imageContainer = document.getElementById('viewStudentImage');
    
    if (student.image && student.image_exists) {
        imageContainer.innerHTML = `<img src="/storage/${student.image}?v=${new Date().getTime()}" alt="Student Image" class="w-full h-64 object-cover rounded-lg">`;
    } else {
        const initials = getInitials(student.name);
        const colors = ['bg-indigo-100', 'bg-green-100', 'bg-yellow-100', 'bg-red-100', 'bg-blue-100', 'bg-purple-100'];
        const colorClass = colors[Math.floor(Math.random() * colors.length)];
        
        imageContainer.innerHTML = `
            <div class="${colorClass} w-full h-64 rounded-lg flex items-center justify-center">
                <span class="text-4xl font-bold text-gray-600">${initials}</span>
            </div>
        `;
    }
}

function loadStudentDocuments(student) {
    const documentsContainer = document.getElementById('viewStudentDocuments');
    
    if ((student.transcript && student.transcript_exists) || (student.recommendation_letter && student.recommendation_letter_exists)) {
        let documentsHTML = '';
        
        if (student.transcript && student.transcript_exists) {
            documentsHTML += `
                <div class="document-card p-3 border border-gray-200 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-file-pdf text-red-500 text-xl mr-3"></i>
                            <div>
                                <p class="font-medium">Transcript</p>
                                <p class="text-sm text-gray-500">PDF Document</p>
                            </div>
                        </div>
                        <a href="/storage/${student.transcript}" target="_blank" class="text-indigo-600 hover:text-indigo-800">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                </div>
            `;
        }
        
        if (student.recommendation_letter && student.recommendation_letter_exists) {
            documentsHTML += `
                <div class="document-card p-3 border border-gray-200 rounded-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-file-alt text-blue-500 text-xl mr-3"></i>
                            <div>
                                <p class="font-medium">Recommendation Letter</p>
                                <p class="text-sm text-gray-500">PDF/DOC Document</p>
                            </div>
                        </div>
                        <a href="/storage/${student.recommendation_letter}" target="_blank" class="text-indigo-600 hover:text-indigo-800">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                </div>
            `;
        }
        
        documentsContainer.innerHTML = documentsHTML;
    } else {
        documentsContainer.innerHTML = `
            <div class="text-center py-4 text-gray-500 border border-gray-200 rounded-lg">
                <i class="fas fa-folder-open text-3xl mb-2"></i>
                <p>No documents available</p>
            </div>
        `;
    }
}

function loadStudentDetails(student) {
    const detailsContainer = document.getElementById('viewStudentDetails');
    
    const genderBadgeClass = {
        'Male': 'bg-blue-100 text-blue-800',
        'Female': 'bg-pink-100 text-pink-800',
        'Other': 'bg-purple-100 text-purple-800'
    }[student.gender] || 'bg-gray-100 text-gray-800';
    
    const detailsHTML = `
        <tr class="border-b border-gray-200 hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-50">Student ID</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-mono">${student.student_id}</td>
        </tr>
        <tr class="border-b border-gray-200 hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-50">Full Name</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${student.name}</td>
        </tr>
        <tr class="border-b border-gray-200 hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-50">Age</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${student.age} years old</td>
        </tr>
        <tr class="border-b border-gray-200 hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-50">Gender</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <span class="px-2 py-1 rounded-full text-xs font-medium ${genderBadgeClass}">
                    ${student.gender}
                </span>
            </td>
        </tr>
        <tr class="border-b border-gray-200 hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-50">Parent Phone</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <a href="tel:${student.parent_phone}" class="text-indigo-600 hover:text-indigo-800">
                    <i class="fas fa-phone-alt mr-1"></i> ${student.parent_phone}
                </a>
            </td>
        </tr>
        <tr class="border-b border-gray-200 hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-50">Class Applying For</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <span class="px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                    ${student.class_applying_for}
                </span>
            </td>
        </tr>
        <tr class="border-b border-gray-200 hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-50">Date of Admission</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <i class="far fa-calendar-alt mr-1 text-gray-500"></i>
                ${new Date(student.date_of_admission).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}
            </td>
        </tr>
        <tr class="border-b border-gray-200 hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-50">Registered On</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <i class="far fa-clock mr-1 text-gray-500"></i>
                ${new Date(student.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })} at ${new Date(student.created_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}
            </td>
        </tr>
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 bg-gray-50">Last Updated</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <i class="fas fa-sync-alt mr-1 text-gray-500"></i>
                ${new Date(student.updated_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })} at ${new Date(student.updated_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}
            </td>
        </tr>
    `;
    
    detailsContainer.innerHTML = detailsHTML;
}

// Close view modal when clicking outside
document.addEventListener('click', function(event) {
    const viewModal = document.getElementById('viewStudentModal');
    if (event.target === viewModal) {
        closeViewStudentModal();
    }
});

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

// ========== EVENT LISTENERS ==========
document.addEventListener('DOMContentLoaded', function() {
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', performSearch);
    }

    // Create student form submission
    const createStudentForm = document.getElementById('createStudentForm');
    if (createStudentForm) {
        createStudentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate files before submission
            if (!validateFiles()) {
                return;
            }
            
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Creating...';
            submitButton.disabled = true;

            const formData = new FormData(this);
            
            // Debug: Log form data to see what's being sent
            console.log('Form data being sent:');
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                
                if (response.redirected) {
                    return { success: true };
                }
                
                return response.text().then(text => {
                    console.log('Raw response:', text);
                    try {
                        const data = JSON.parse(text);
                        console.log('Parsed response:', data);
                        return data;
                    } catch (e) {
                        console.error('JSON parse error:', e);
                        // If it's not JSON but we got a successful status, assume success
                        if (response.ok) {
                            return { success: true };
                        }
                        throw new Error('Server returned invalid response');
                    }
                });
            })
            .then(data => {
                console.log('Final data:', data);
                if (data.success) {
                    closeCreateStudentModal();
                    showSuccessMessage('Student created successfully!');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        let errorMessage = 'Please fix the following errors: ';
                        Object.values(data.errors).forEach(error => {
                            errorMessage += error[0] + ' ';
                        });
                        throw new Error(errorMessage);
                    }
                    throw new Error(data.message || 'Failed to create student');
                }
            })
            .catch(error => {
                console.error('Full error:', error);
                showErrorMessage('Failed to create student: ' + error.message);
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            });
        });
    }

    // Delete student form submission
    const deleteStudentForm = document.getElementById('deleteStudentForm');
    if (deleteStudentForm) {
        deleteStudentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Deleting...';
            submitButton.disabled = true;

            fetch(this.action, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
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
                    closeDeleteModal();
                    showSuccessMessage('Student deleted successfully!');
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    throw new Error(data.message || 'Failed to delete student');
                }
            })
            .catch(error => {
                console.error('Delete error:', error);
                showErrorMessage('Failed to delete student: ' + error.message);
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            });
        });
    }

    // Close modals when clicking outside
    document.addEventListener('click', function(event) {
        const createModal = document.getElementById('createStudentModal');
        if (event.target === createModal) {
            closeCreateStudentModal();
        }
        
        const deleteModal = document.getElementById('deleteStudentModal');
        if (event.target === deleteModal) {
            closeDeleteModal();
        }
    });
});

// File validation function
function validateFiles() {
    const imageInput = document.getElementById('image');
    const transcriptInput = document.getElementById('transcript');
    const recommendationInput = document.getElementById('recommendation_letter');
    
    let isValid = true;
    let errorMessage = '';
    
    // Validate image if provided
    if (imageInput.files.length > 0) {
        const imageFile = imageInput.files[0];
        const validImageTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        const maxImageSize = 2 * 1024 * 1024; // 2MB
        
        if (!validImageTypes.includes(imageFile.type)) {
            isValid = false;
            errorMessage = 'Please select a valid image file (JPG, JPEG, PNG, GIF).';
        } else if (imageFile.size > maxImageSize) {
            isValid = false;
            errorMessage = 'Image file size must be less than 2MB.';
        }
    }
    
    // Validate transcript if provided
    if (transcriptInput.files.length > 0) {
        const transcriptFile = transcriptInput.files[0];
        const validDocTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        const maxDocSize = 5 * 1024 * 1024; // 5MB
        
        if (!validDocTypes.includes(transcriptFile.type)) {
            isValid = false;
            errorMessage = 'Please select a valid transcript file (PDF, DOC, DOCX).';
        } else if (transcriptFile.size > maxDocSize) {
            isValid = false;
            errorMessage = 'Transcript file size must be less than 5MB.';
        }
    }
    
    // Validate recommendation letter if provided
    if (recommendationInput.files.length > 0) {
        const recommendationFile = recommendationInput.files[0];
        const validDocTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
        const maxDocSize = 5 * 1024 * 1024; // 5MB
        
        if (!validDocTypes.includes(recommendationFile.type)) {
            isValid = false;
            errorMessage = 'Please select a valid recommendation letter file (PDF, DOC, DOCX).';
        } else if (recommendationFile.size > maxDocSize) {
            isValid = false;
            errorMessage = 'Recommendation letter file size must be less than 5MB.';
        }
    }
    
    if (!isValid) {
        showErrorMessage(errorMessage);
    }
    
    return isValid;
}
    </script>
@endsection