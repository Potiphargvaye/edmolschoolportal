@extends('layouts.teacher')

@section('title', 'Create Material - WKG')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Create New Material</h1>
            <p class="text-gray-600">Add assignments, notes, or resources for your students</p>
        </div>

        <!-- Form -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <form action="{{ route('teacher.materials.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-6 space-y-6">
                    <!-- Basic Information -->
                    <div>
                        <h3 class="text-lg font-medium text-gray-800 mb-4">Basic Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="form-label">Title *</label>
                                <input type="text" name="title" required class="form-input" placeholder="Enter material title">
                            </div>
                            <div>
                                <label class="form-label">Type *</label>
                                <select name="type" required class="form-input">
                                    <option value="">Select Type</option>
                                    <option value="assignment">Assignment</option>
                                    <option value="note">Note</option>
                                    <option value="resource">Resource</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Grade Selection -->
                    <div>
                        <label class="form-label">Grade *</label>
                        <select name="grade_id" required class="form-input">
                            <option value="">Select Grade</option>
                            @foreach($grades as $grade)
                                <option value="{{ $grade->id }}">Grade {{ $grade->level }}{{ $grade->section }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="3" class="form-input" placeholder="Describe the material..."></textarea>
                    </div>

                    <!-- File Upload -->
                    <div>
                        <label class="form-label">Attachment</label>
                        <div class="file-upload-container">
                            <input type="file" name="file" class="file-input" id="fileInput">
                            <label for="fileInput" class="file-upload-label">
                                <i class="fas fa-cloud-upload-alt text-2xl text-gray-400 mb-2"></i>
                                <span class="text-gray-600">Click to upload or drag and drop</span>
                                <span class="text-sm text-gray-400">PDF, DOC, DOCX, TXT, JPG, PNG (Max: 2MB)</span>
                            </label>
                        </div>
                        <div id="fileName" class="file-name-display hidden mt-2"></div>
                    </div>

                    <!-- Assignment Specific Fields -->
                    <div id="assignmentFields" class="hidden space-y-4">
                        <h3 class="text-lg font-medium text-gray-800">Assignment Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="form-label">Due Date</label>
                                <input type="datetime-local" name="due_date" class="form-input">
                            </div>
                            <div>
                                <label class="form-label">Maximum Score</label>
                                <input type="number" name="max_score" min="0" class="form-input" placeholder="Enter max score">
                            </div>
                        </div>
                    </div>

                    <!-- Publish Option -->
                    <div class="flex items-center">
                        <input type="checkbox" name="is_published" id="is_published" class="form-checkbox">
                        <label for="is_published" class="ml-2 text-gray-700">Publish immediately</label>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end space-x-3">
                    <a href="{{ route('teacher.materials.index') }}" class="btn-secondary">
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i> Create Material
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .form-label {
        display: block;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }

    .form-input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .form-input:focus {
        outline: none;
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .form-checkbox {
        border-radius: 4px;
        border: 1px solid #d1d5db;
    }

    .file-upload-container {
        position: relative;
    }

    .file-input {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        border: 0;
    }

    .file-upload-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        border: 2px dashed #d1d5db;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .file-upload-label:hover {
        border-color: #4f46e5;
        background: #f9fafb;
    }

    .file-name-display {
        background: #f9fafb;
        padding: 10px 14px;
        border-radius: 6px;
        border: 1px solid #e5e7eb;
    }

    .btn-secondary {
        display: inline-flex;
        align-items: center;
        padding: 10px 20px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        color: #374151;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background: #f9fafb;
        border-color: #9ca3af;
    }
</style>

<script>
    // Show/hide assignment fields based on type selection
    document.querySelector('select[name="type"]').addEventListener('change', function(e) {
        const assignmentFields = document.getElementById('assignmentFields');
        if (e.target.value === 'assignment') {
            assignmentFields.classList.remove('hidden');
        } else {
            assignmentFields.classList.add('hidden');
        }
    });

    // Show selected file name
    document.getElementById('fileInput').addEventListener('change', function(e) {
        const fileNameDisplay = document.getElementById('fileName');
        if (this.files.length > 0) {
            fileNameDisplay.textContent = this.files[0].name;
            fileNameDisplay.classList.remove('hidden');
        } else {
            fileNameDisplay.classList.add('hidden');
        }
    });
</script>
@endsection