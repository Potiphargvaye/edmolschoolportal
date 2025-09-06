@extends('layouts.teacher')

@section('title', 'Manage Materials - WKG')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Teaching Materials</h1>
                    <p class="text-gray-600">Manage your assignments, notes, and resources</p>
                </div>
                <a href="{{ route('teacher.materials.create') }}" class="btn-primary mt-4 md:mt-0">
                    <i class="fas fa-plus mr-2"></i> Add New Material
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:space-x-4 space-y-4 md:space-y-0">
                <div class="flex-1">
                    <input type="text" placeholder="Search materials..." class="search-input">
                </div>
                <div class="flex space-x-4">
                    <select class="filter-select">
                        <option value="">All Types</option>
                        <option value="assignment">Assignments</option>
                        <option value="note">Notes</option>
                        <option value="resource">Resources</option>
                    </select>
                    <select class="filter-select">
                        <option value="">All Grades</option>
                        @foreach($grades as $grade)
                            <option value="{{ $grade->id }}">Grade {{ $grade->level }}{{ $grade->section }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Materials Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($materials as $material)
            <div class="material-card bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <span class="material-badge material-badge-{{ $material->type }}">
                                {{ ucfirst($material->type) }}
                            </span>
                        </div>
                       
                        <!-- Dropdown Menu Container -->
                        <div class="flex space-x-2 relative">
                            <button class="action-btn action-btn-edit" onclick="toggleDropdown('dropdown-{{ $material->id }}')">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <!-- Dropdown Menu -->
                            <div id="dropdown-{{ $material->id }}" class="dropdown-menu-container hidden">
                                <div class="dropdown-menu-content">
                                    <a href="{{ route('teacher.materials.edit', $material) }}" class="dropdown-item">
                                        <i class="fas fa-edit mr-2"></i> Edit
                                    </a>
                                    <form action="{{ route('teacher.materials.destroy', $material) }}" method="POST" class="dropdown-item">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Are you sure?')" class="w-full text-left">
                                            <i class="fas fa-trash mr-2"></i> Delete
                                        </button>
                                    </form>
                                    <form action="{{ route('teacher.materials.toggle-publish', $material) }}" method="POST" class="dropdown-item">
                                        @csrf
                                        <button type="submit" class="w-full text-left">
                                            <i class="fas fa-{{ $material->is_published ? 'eye-slash' : 'eye' }} mr-2"></i>
                                            {{ $material->is_published ? 'Unpublish' : 'Publish' }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">{{ $material->title }}</h3>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $material->description }}</p>

                    <!-- Meta Information -->
                    <div class="space-y-2">
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-users-class mr-2"></i>
                            <span>Grade {{ $material->grade->level }}{{ $material->grade->section }}</span>
                        </div>
                        
                        @if($material->due_date)
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-calendar-alt mr-2"></i>
                            <span>Due: {{ $material->due_date->format('M d, Y') }}</span>
                        </div>
                        @endif

                        @if($material->max_score)
                        <div class="flex items-center text-sm text-gray-500">
                            <i class="fas fa-star mr-2"></i>
                            <span>Max Score: {{ $material->max_score }}</span>
                        </div>
                        @endif

                        @if($material->file_path)
                        <a href="{{ asset('storage/' . $material->file_path) }}" target="_blank" class="flex items-center text-sm text-blue-600 hover:text-blue-800 hover:underline cursor-pointer">
                            <i class="fas fa-paperclip mr-2"></i>
                            <span>View Attachment</span>
                        </a>
                        @endif
                    </div>

                    <!-- Status -->
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <div class="flex items-center justify-between">
                            <span class="status-badge status-{{ $material->is_published ? 'published' : 'draft' }}">
                                {{ $material->is_published ? 'Published' : 'Draft' }}
                            </span>
                            <span class="text-xs text-gray-400">
                                {{ $material->created_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full">
                <div class="text-center py-12">
                    <i class="fas fa-tasks text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-600">No materials yet</h3>
                    <p class="text-gray-500 mt-2">Get started by creating your first assignment or note</p>
                    <a href="{{ route('teacher.materials.create') }}" class="btn-primary mt-4">
                        <i class="fas fa-plus mr-2"></i> Create Material
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($materials->hasPages())
        <div class="mt-8">
            {{ $materials->links() }}
        </div>
        @endif
    </div>
</div>

<style>
    /* Material Management Styles */
    .btn-primary {
        display: inline-flex;
        align-items: center;
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(79, 70, 229, 0.2);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(79, 70, 229, 0.3);
    }

    .search-input {
        width: 100%;
        padding: 10px 16px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        transition: border-color 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .filter-select {
        padding: 10px 12px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: white;
        transition: border-color 0.3s ease;
    }

    .filter-select:focus {
        outline: none;
        border-color: #4f46e5;
    }

    .material-card {
        transition: all 0.3s ease;
        border: 1px solid #f3f4f6;
    }

    .material-card:hover {
        transform: translateY(-2px);
    }

    .material-badge {
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .material-badge-assignment {
        background: #fee2e2;
        color: #dc2626;
    }

    .material-badge-note {
        background: #dbeafe;
        color: #2563eb;
    }

    .material-badge-resource {
        background: #dcfce7;
        color: #16a34a;
    }

    .action-btn {
        padding: 6px;
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .action-btn-edit {
        color: #6b7280;
    }

    .action-btn-edit:hover {
        background: #f3f4f6;
        color: #374151;
    }

    /* Fixed Dropdown Styles */
    .dropdown-menu-container {
        position: absolute;
        right: 0;
        top: 100%;
        z-index: 50;
        margin-top: 0.5rem;
    }

    .dropdown-menu-content {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        min-width: 160px;
        padding: 0.5rem 0;
    }

    .dropdown-item {
        display: block;
        padding: 10px 16px;
        color: #374151;
        text-decoration: none;
        transition: background 0.3s ease;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
    }

    .dropdown-item:hover {
        background: #f9fafb;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .status-badge {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 600;
    }

    .status-published {
        background: #dcfce7;
        color: #16a34a;
    }

    .status-draft {
        background: #fef3c7;
        color: #d97706;
    }

    /* Pagination Styles */
    .pagination {
        display: flex;
        justify-content: center;
        list-style: none;
        padding: 0;
    }

    .pagination li {
        margin: 0 4px;
    }

    .pagination a {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        color: #6b7280;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .pagination a:hover {
        background: #f9fafb;
        color: #374151;
    }

    .pagination .active a {
        background: #4f46e5;
        color: white;
        border-color: #4f46e5;
    }

    /* Attachment link */
    .attachment-link {
        transition: all 0.2s ease;
    }

    .attachment-link:hover {
        transform: translateX(2px);
    }
</style>

<script>
    function toggleDropdown(dropdownId) {
        const dropdown = document.getElementById(dropdownId);
        
        // Close all other dropdowns first
        document.querySelectorAll('.dropdown-menu-container').forEach(menu => {
            if (menu.id !== dropdownId) {
                menu.classList.add('hidden');
            }
        });
        
        // Toggle the current dropdown
        dropdown.classList.toggle('hidden');
    }

    // Close dropdowns when clicking outside
    document.addEventListener('click', function(event) {
        if (!event.target.closest('.action-btn') && !event.target.closest('.dropdown-menu-container')) {
            document.querySelectorAll('.dropdown-menu-container').forEach(menu => {
                menu.classList.add('hidden');
            });
        }
    });

    // Prevent dropdown from closing when clicking inside it
    document.querySelectorAll('.dropdown-menu-container').forEach(menu => {
        menu.addEventListener('click', function(event) {
            event.stopPropagation();
        });
    });
</script>
@endsection