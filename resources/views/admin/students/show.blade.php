<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details - {{ $student->student_id }}</title>
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
        .btn-primary {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            transition: all 0.3s ease;
            border: none;
            border-radius: 8px;
            padding: 0.5rem 1rem;
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
            padding: 0.5rem 1rem;
            font-weight: 500;
            color: #4b5563;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .btn-secondary:hover {
            background: #f9fafb;
            border-color: #d1d5db;
        }
        .back-btn {
            transition: all 0.3s ease;
            color: #e0e7ff;
        }
        .back-btn:hover {
            transform: translateX(-4px);
            color: white;
        }
        .detail-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }
        .detail-table th {
            background: #f8fafc;
            padding: 0.75rem 1rem;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
            width: 30%;
        }
        .detail-table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f3f4f6;
            color: #374151;
        }
        .detail-table tr:last-child th,
        .detail-table tr:last-child td {
            border-bottom: none;
        }
        .document-card {
            background: #f8fafc;
            border-radius: 8px;
            padding: 1rem;
            transition: all 0.3s ease;
        }
        .document-card:hover {
            background: #f0f5ff;
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        .avatar-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            background: #e5e7eb;
            border-radius: 12px;
            height: 200px;
            color: #6b7280;
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
    <div class="max-w-6xl mx-auto">
        <!-- Banner Container -->
        <div class="banner text-white p-6 mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h1 class="text-2xl sm:text-3xl font-bold">Student Details: {{ $student->student_id }}</h1>
            <a href="{{ route('admin.students.index') }}" class="flex items-center gap-2 font-medium back-btn">
                <i class="fas fa-arrow-left"></i>
                Back to Students List
            </a>
        </div>

        <!-- Details Card -->
        <div class="card p-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
                <h2 class="text-xl font-semibold text-gray-800">Student Information</h2>
                <div class="flex space-x-2 mt-4 sm:mt-0">
                    <a href="{{ route('admin.students.edit', $student) }}" class="btn-primary">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>
                    <a href="{{ route('admin.students.index') }}" class="btn-secondary">
                        <i class="fas fa-list mr-1"></i> Back to List
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Student Image & Documents -->
                <div class="lg:col-span-1">
                    <div class="mb-6">
                        @if($student->image)
                            <img src="{{ asset('storage/' . $student->image) }}" alt="Student Image" class="w-full h-64 object-cover rounded-lg shadow-md">
                        @else
                            @php
                                $initials = implode('', array_map(function($word) {
                                    return strtoupper(substr(trim($word), 0, 1));
                                }, array_slice(explode(' ', $student->name), 0, 2)));
                                
                                $colors = ['bg-indigo-100', 'bg-green-100', 'bg-yellow-100', 'bg-red-100', 'bg-blue-100', 'bg-purple-100'];
                                $colorClass = $colors[array_rand($colors)];
                            @endphp
                            <div class="{{ $colorClass }} avatar-placeholder">
                                <span class="text-4xl font-bold">{{ $initials ?: '?' }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Documents Section -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-3">Documents</h3>
                        
                        @if($student->transcript || $student->recommendation_letter)
                            <div class="space-y-3">
                                @if($student->transcript)
                                    <div class="document-card">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <i class="fas fa-file-pdf text-red-500 text-xl mr-3"></i>
                                                <div>
                                                    <p class="font-medium">Transcript</p>
                                                    <p class="text-sm text-gray-500">PDF Document</p>
                                                </div>
                                            </div>
                                            <a href="{{ asset('storage/' . $student->transcript) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($student->recommendation_letter)
                                    <div class="document-card">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <i class="fas fa-file-alt text-blue-500 text-xl mr-3"></i>
                                                <div>
                                                    <p class="font-medium">Recommendation Letter</p>
                                                    <p class="text-sm text-gray-500">PDF/DOC Document</p>
                                                </div>
                                            </div>
                                            <a href="{{ asset('storage/' . $student->recommendation_letter) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-4 text-gray-500">
                                <i class="fas fa-folder-open text-3xl mb-2"></i>
                                <p>No documents available</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Student Details -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                        <table class="detail-table">
                            <tr>
                                <th>Student ID</th>
                                <td class="font-mono font-medium">{{ $student->student_id }}</td>
                            </tr>
                            <tr>
                                <th>Full Name</th>
                                <td>{{ $student->name }}</td>
                            </tr>
                            <tr>
                                <th>Age</th>
                                <td>{{ $student->age }} years old</td>
                            </tr>
                            <tr>
                                <th>Gender</th>
                                <td>
                                    <span class="px-2 py-1 rounded-full text-xs font-medium 
                                        {{ $student->gender == 'Male' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $student->gender == 'Female' ? 'bg-pink-100 text-pink-800' : '' }}
                                        {{ $student->gender == 'Other' ? 'bg-purple-100 text-purple-800' : '' }}">
                                        {{ $student->gender }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Parent Phone</th>
                                <td>
                                    <a href="tel:{{ $student->parent_phone }}" class="text-indigo-600 hover:text-indigo-800">
                                        <i class="fas fa-phone-alt mr-1"></i> {{ $student->parent_phone }}
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th>Class Applying For</th>
                                <td>
                                    <span class="px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                        {{ $student->class_applying_for }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Date of Admission</th>
                                <td>
                                    <i class="far fa-calendar-alt mr-1 text-gray-500"></i>
                                    {{ $student->date_of_admission->format('F d, Y') }}
                                </td>
                            </tr>
                            <tr>
                                <th>Registered On</th>
                                <td>
                                    <i class="far fa-clock mr-1 text-gray-500"></i>
                                    {{ $student->created_at->format('F d, Y \\a\\t h:i A') }}
                                </td>
                            </tr>
                            <tr>
                                <th>Last Updated</th>
                                <td>
                                    <i class="fas fa-sync-alt mr-1 text-gray-500"></i>
                                    {{ $student->updated_at->format('F d, Y \\a\\t h:i A') }}
                                </td>
                            </tr>
                        </table>
                    </div>

                    <!-- Additional Actions -->
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('admin.students.edit', $student) }}" class="btn-primary">
                            <i class="fas fa-edit mr-1"></i> Edit Student
                        </a>
                        <form action="{{ route('admin.students.destroy', $student) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-secondary text-red-600 hover:bg-red-50 hover:text-red-700" onclick="return confirm('Are you sure you want to delete this student?')">
                                <i class="fas fa-trash mr-1"></i> Delete Student
                            </button>
                        </form>
                        <a href="{{ route('admin.students.index') }}" class="btn-secondary">
                            <i class="fas fa-list mr-1"></i> View All Students
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>