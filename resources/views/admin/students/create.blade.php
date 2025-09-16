<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management - Register Student</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            min-height: 100vh;
        }
        .banner {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(79, 70, 229, 0.2);
        }
        .card {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 5px 10px -5px rgba(0, 0, 0, 0.04);
            border-radius: 12px;
            transition: all 0.3s ease;
            background: white;
        }
        .form-input {
            transition: all 0.3s ease;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 0.75rem 1rem;
            width: 100%;
            font-size: 0.95rem;
        }
        .form-input:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.15);
        }
        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
            display: block;
            font-size: 0.95rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            transition: all 0.3s ease;
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            color: white;
            box-shadow: 0 4px 6px rgba(79, 70, 229, 0.2);
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(79, 70, 229, 0.3);
        }
        .btn-secondary {
            background: white;
            transition: all 0.3s ease;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            color: #4b5563;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .btn-secondary:hover {
            background: #f9fafb;
            border-color: #d1d5db;
        }
        .file-input {
            position: relative;
            overflow: hidden;
        }
        .file-input input[type="file"] {
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }
        .file-input-label {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1rem;
            background: #f9fafb;
            border: 1px dashed #d1d5db;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .file-input-label:hover {
            border-color: #4f46e5;
            background: #f0f5ff;
        }
        .preview-image {
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        .back-btn {
            transition: all 0.3s ease;
            color: #e0e7ff;
        }
        .back-btn:hover {
            transform: translateX(-4px);
            color: white;
        }
        .form-section {
            padding: 1.5rem;
            border-radius: 8px;
            background: #f8fafc;
            margin-bottom: 1.5rem;
        }
        .form-section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #4f46e5;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e0e7ff;
        }
        select.form-input {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%234b5563'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1rem;
            padding-right: 2.5rem;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }
        @media (max-width: 768px) {
            .card {
                margin: 1rem;
                padding: 1rem;
            }
            .banner {
                border-radius: 8px;
                margin: 1rem;
                padding: 1.25rem;
            }
        }
    </style>
</head>
<body class="py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        <!-- Banner Container -->
        <div class="banner text-white p-6 mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h1 class="text-2xl sm:text-3xl font-bold">Register New Student</h1>
            <a href="{{ route('admin.students.index') }}" class="flex items-center gap-2 font-medium back-btn">
                <i class="fas fa-arrow-left"></i>
                Back to Students List
            </a>
        </div>

        <!-- Form Card -->
        <div class="card p-6">
            <form action="{{ route('admin.students.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <!-- Personal Information Section -->
                <div class="form-section">
                    <h2 class="form-section-title">Personal Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="form-label">Full Name <span class="text-red-500">*</span></label>
                            <input type="text" class="form-input @error('name') border-red-500 @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="age" class="form-label">Age <span class="text-red-500">*</span></label>
                            <input type="number" class="form-input @error('age') border-red-500 @enderror" id="age" name="age" value="{{ old('age') }}" required min="5" max="25">
                            @error('age')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="gender" class="form-label">Gender <span class="text-red-500">*</span></label>
                            <select class="form-input @error('gender') border-red-500 @enderror" id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>male</option>
                                <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>female</option>
                                <option value="Other" {{ old('gender') == 'Other' ? 'selected' : '' }}>other</option>
                            </select>
                            @error('gender')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="parent_phone" class="form-label">Parent Phone Number <span class="text-red-500">*</span></label>
                            <input type="text" class="form-input @error('parent_phone') border-red-500 @enderror" id="parent_phone" name="parent_phone" value="{{ old('parent_phone') }}" required>
                            @error('parent_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Academic Information Section -->
                <div class="form-section">
                    <h2 class="form-section-title">Academic Information</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="class_applying_for" class="form-label">Class Applying For <span class="text-red-500">*</span></label>
                            <select class="form-input @error('class_applying_for') border-red-500 @enderror" id="class_applying_for" name="class_applying_for" required>
                                <option value="">Select Class</option>
                                <optgroup label="Nursery">
                                    <option value="Nursery 1" {{ old('class_applying_for') == 'Nursery 1' ? 'selected' : '' }}>Nursery 1</option>
                                    <option value="Nursery 2" {{ old('class_applying_for') == 'Nursery 2' ? 'selected' : '' }}>Nursery 2</option>
                                    <option value="Nursery 3" {{ old('class_applying_for') == 'Nursery 3' ? 'selected' : '' }}>Nursery 3</option>
                                </optgroup>
                                <optgroup label="Primary">
                                    <option value="Grade 1" {{ old('class_applying_for') == 'Grade 1' ? 'selected' : '' }}>Grade 1</option>
                                    <option value="Grade 2" {{ old('class_applying_for') == 'Grade 2' ? 'selected' : '' }}>Grade 2</option>
                                    <option value="Grade 3" {{ old('class_applying_for') == 'Grade 3' ? 'selected' : '' }}>Grade 3</option>
                                    <option value="Grade 4" {{ old('class_applying_for') == 'Grade 4' ? 'selected' : '' }}>Grade 4</option>
                                    <option value="Grade 5" {{ old('class_applying_for') == 'Grade 5' ? 'selected' : '' }}>Grade 5</option>
                                    <option value="Grade 6" {{ old('class_applying_for') == 'Grade 6' ? 'selected' : '' }}>Grade 6</option>
                                </optgroup>
                                <optgroup label="Secondary">
                                    <option value="Grade 7" {{ old('class_applying_for') == 'Grade 7' ? 'selected' : '' }}>Grade 7</option>
                                    <option value="Grade 8" {{ old('class_applying_for') == 'Grade 8' ? 'selected' : '' }}>Grade 8</option>
                                    <option value="Grade 9" {{ old('class_applying_for') == 'Grade 9' ? 'selected' : '' }}>Grade 9</option>
                                    <option value="Grade 10" {{ old('class_applying_for') == 'Grade 10' ? 'selected' : '' }}>Grade 10</option>
                                    <option value="Grade 11" {{ old('class_applying_for') == 'Grade 11' ? 'selected' : '' }}>Grade 11</option>
                                    <option value="Grade 12" {{ old('class_applying_for') == 'Grade 12' ? 'selected' : '' }}>Grade 12</option>
                                </optgroup>
                            </select>
                            @error('class_applying_for')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="date_of_admission" class="form-label">Date of Admission <span class="text-red-500">*</span></label>
                            <input type="date" class="form-input @error('date_of_admission') border-red-500 @enderror" id="date_of_admission" name="date_of_admission" value="{{ old('date_of_admission') }}" required>
                            @error('date_of_admission')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Documents Section -->
                <div class="form-section">
                    <h2 class="form-section-title">Documents</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="form-label">Student Image</label>
                            <div class="file-input">
                                <label class="file-input-label">
                                    <span class="flex items-center gap-2">
                                        <i class="fas fa-upload"></i>
                                        Choose Image
                                    </span>
                                    <input type="file" id="image" name="image" class="@error('image') border-red-500 @enderror">
                                </label>
                            </div>
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="form-label">Transcript (PDF/DOC)</label>
                            <div class="file-input">
                                <label class="file-input-label">
                                    <span class="flex items-center gap-2">
                                        <i class="fas fa-file-pdf"></i>
                                        Upload Transcript
                                    </span>
                                    <input type="file" id="transcript" name="transcript" class="@error('transcript') border-red-500 @enderror">
                                </label>
                            </div>
                            @error('transcript')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="form-label">Recommendation Letter (PDF/DOC)</label>
                            <div class="file-input">
                                <label class="file-input-label">
                                    <span class="flex items-center gap-2">
                                        <i class="fas fa-file-alt"></i>
                                        Upload Recommendation Letter
                                    </span>
                                    <input type="file" id="recommendation_letter" name="recommendation_letter" class="@error('recommendation_letter') border-red-500 @enderror">
                                </label>
                            </div>
                            @error('recommendation_letter')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-6">
                    <a href="{{ route('admin.students.index') }}" class="btn-secondary text-center">
                        Cancel
                    </a>
                    <button type="submit" class="btn-primary">
                        Register Student
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // File input preview and validation
        document.addEventListener('DOMContentLoaded', function() {
            // Image preview
            const imageInput = document.getElementById('image');
            if (imageInput) {
                imageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            // Remove existing preview if any
                            const existingPreview = document.getElementById('image-preview');
                            if (existingPreview) {
                                existingPreview.remove();
                            }
                            
                            // Create new preview
                            const previewDiv = document.createElement('div');
                            previewDiv.id = 'image-preview';
                            previewDiv.className = 'mt-3';
                            previewDiv.innerHTML = `
                                <p class="text-sm text-gray-600 mb-1">Image Preview:</p>
                                <img src="${e.target.result}" alt="Image Preview" class="preview-image h-24 w-24 object-cover">
                            `;
                            
                            // Insert after the file input
                            imageInput.closest('div').appendChild(previewDiv);
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }
            
            // File type validation
            const fileInputs = document.querySelectorAll('input[type="file"]');
            fileInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const file = this.files[0];
                    if (file) {
                        const fileName = file.name;
                        const fileExtension = fileName.split('.').pop().toLowerCase();
                        
                        // Validate image files
                        if (this.id === 'image') {
                            const validImageTypes = ['jpg', 'jpeg', 'png', 'gif'];
                            if (!validImageTypes.includes(fileExtension)) {
                                alert('Please select a valid image file (JPG, JPEG, PNG, GIF).');
                                this.value = '';
                            }
                        }
                        
                        // Validate document files
                        if (this.id === 'transcript' || this.id === 'recommendation_letter') {
                            const validDocTypes = ['pdf', 'doc', 'docx'];
                            if (!validDocTypes.includes(fileExtension)) {
                                alert('Please select a valid document file (PDF, DOC, DOCX).');
                                this.value = '';
                            }
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>