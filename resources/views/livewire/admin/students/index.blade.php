

<div class="p-4 sm:p-6 bg-white rounded-xl shadow space-y-4">
@include('partials.notifications')
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
        <h1 class="text-2xl font-bold">Students</h1>
        <div class="flex flex-wrap gap-2">
            <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 flex items-center gap-1">
                SMS
            </button>
            <button class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 flex items-center gap-1">
                Email
            </button>
            <button wire:click="$set('showAddModal', true)"
                class="px-4 py-2 bg-[#f84525] text-white rounded hover:bg-red-600 flex items-center gap-1">
                <i class="ri-add-line"></i> Register New
            </button>
        </div>
    </div>

    {{-- Status Tabs --}}
    <div class="flex flex-wrap gap-2 border-b pb-2 mt-2">
        @foreach(['candidate','admitted','registered','active','dropout','completed'] as $tab)
            <button wire:click="$set('status','{{ $tab }}')"
                class="px-3 py-1 rounded font-semibold text-sm
                {{ $status === $tab ? 'bg-[#f84525] text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                {{ ucfirst($tab) }}
            </button>
        @endforeach
    </div>

    {{-- Filters --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:space-x-2 mt-3 gap-2">
        <input type="text" wire:model="search" placeholder="Search name..."
               class="w-full sm:w-1/3 px-3 py-2 border rounded text-sm">
        <select wire:model="intake" class="w-full sm:w-1/4 px-3 py-2 border rounded text-sm">
            <option value="">Select Intake</option>
        </select>
        <select wire:model="shift" class="w-full sm:w-1/4 px-3 py-2 border rounded text-sm">
            <option value="">Select Shift</option>
        </select>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto mt-4">
        <table class="w-full table-auto border-collapse border border-gray-200 text-sm">
            <thead class="bg-gray-100">
                <tr class="text-center">
                    <th class="px-3 py-2 border">ID</th>
                    <th class="px-3 py-2 border">Name</th>
                    <th class="px-3 py-2 border">Age</th>
                    <th class="px-3 py-2 border">Gender</th>
                    <th class="px-3 py-2 border">Class</th>
                    <th class="px-3 py-2 border">Status</th>
                    <th class="px-3 py-2 border">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $student)
                <tr class="text-center border-b">
                    <td class="px-2 py-1 border">{{ $student->student_id }}</td>
                    <td class="px-2 py-1 border">{{ $student->name }}</td>
                    <td class="px-2 py-1 border">{{ $student->age }}</td>
                    <td class="px-2 py-1 border">{{ $student->gender }}</td>
                    <td class="px-2 py-1 border">{{ $student->class_applying_for }}</td>
                    <td class="px-2 py-1 border capitalize">{{ $student->status }}</td>
                    <td class="px-2 py-1 border flex flex-wrap justify-center gap-1">

                        {{-- Dynamic Status Buttons --}}
@if($student->status === 'candidate')
    <button
        wire:click="changeStatus({{ $student->id }}, 'admitted')"
        wire:loading.attr="disabled"
        wire:target="changeStatus({{ $student->id }}, 'admitted')"
        class="px-2 py-1 bg-green-500 text-white rounded text-xs
               flex items-center justify-center gap-1"
    >
        <span
            wire:loading
            wire:target="changeStatus({{ $student->id }}, 'admitted')"
            class="animate-spin h-3 w-3 border-2 border-white border-t-transparent rounded-full"
        ></span>

        <span
            wire:loading.remove
            wire:target="changeStatus({{ $student->id }}, 'admitted')"
        >
            Admit
        </span>
    </button>

@elseif($student->status === 'admitted')
    <button
        wire:click="changeStatus({{ $student->id }}, 'registered')"
        wire:loading.attr="disabled"
        class="px-2 py-1 bg-blue-500 text-white rounded text-xs
               flex items-center justify-center gap-1"
    >
        Register
    </button>

@elseif($student->status === 'registered')
    <button
        wire:click="changeStatus({{ $student->id }}, 'active')"
        wire:loading.attr="disabled"
        class="px-2 py-1 bg-indigo-500 text-white rounded text-xs
               flex items-center justify-center gap-1"
    >
        Activate
    </button>

@elseif($student->status === 'active')
    <div class="flex gap-1">
        <button
            wire:click="changeStatus({{ $student->id }}, 'completed')"
            wire:loading.attr="disabled"
            wire:target="changeStatus({{ $student->id }}, 'completed')"
            class="px-2 py-1 bg-gray-700 text-white rounded text-xs
                   flex items-center justify-center gap-1"
        >
            <span
                wire:loading
                wire:target="changeStatus({{ $student->id }}, 'completed')"
                class="animate-spin h-3 w-3 border-2 border-white border-t-transparent rounded-full"
            ></span>

            <span
                wire:loading.remove
                wire:target="changeStatus({{ $student->id }}, 'completed')"
            >
                Complete
            </span>
        </button>

        <button
            wire:click="changeStatus({{ $student->id }}, 'dropout')"
            wire:loading.attr="disabled"
            wire:target="changeStatus({{ $student->id }}, 'dropout')"
            class="px-2 py-1 bg-red-500 text-white rounded text-xs
                   flex items-center justify-center gap-1"
        >
            <span
                wire:loading
                wire:target="changeStatus({{ $student->id }}, 'dropout')"
                class="animate-spin h-3 w-3 border-2 border-white border-t-transparent rounded-full"
            ></span>

            <span
                wire:loading.remove
                wire:target="changeStatus({{ $student->id }}, 'dropout')"
            >
                Dropout
            </span>
        </button>
    </div>
@endif



                        {{-- View / Edit / Delete Icons --}}
                        <button wire:click="viewStudent({{ $student->id }})"
                            class="text-blue-500 hover:text-blue-700" title="View">
                            <i class="ri-eye-line text-lg"></i>
                        </button>

                        <button wire:click="editStudent({{ $student->id }})"
                            class="text-yellow-500 hover:text-yellow-700" title="Edit">
                            <i class="ri-edit-line text-lg"></i>
                        </button>

                        <button wire:click="deleteStudent({{ $student->id }})"
                            class="text-red-500 hover:text-red-700" title="Delete">
                            <i class="ri-delete-bin-line text-lg"></i>
                        </button>

                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-4 text-gray-500 text-center">No students found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $students->links() }}
    </div>



{{-- Add New Student Modal --}}
<div
    x-data="{ open: @entangle('showAddModal') }"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm px-3 overflow-y-auto"
>

    <!-- Modal Box -->
    <div
        x-show="open"
        x-transition
        class="bg-white w-full max-w-3xl rounded-2xl shadow-2xl p-5 relative mt-10 sm:mt-0 flex flex-col max-h-[85vh] overflow-y-auto"
    >
        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-3 bg-blue-900 text-white rounded-t-2xl">
            <div>
                <h2 class="text-lg font-semibold">Register New Student</h2>
            </div>
            <button
                @click="open = false"
                class="p-2 rounded-full hover:bg-red-500 transition"
            >
                <i class="ri-close-line text-xl"></i>
            </button>
        </div>

        <!-- Spinner (Livewire loading) -->
        <div
            wire:loading.flex
            wire:target="storeStudent"
            class="absolute inset-0 bg-white/70 z-50 items-center justify-center"
        >
            <div class="animate-spin rounded-full h-10 w-10 border-4 border-blue-500 border-t-transparent"></div>
        </div>

        <!-- Body -->
        <div class="p-4 space-y-6">
            <form wire:submit.prevent="storeStudent" class="space-y-4 max-h-[70vh] overflow-y-auto pr-2">

                <!-- Name -->
                <div>
                    <label class="text-sm font-medium">Full Name *</label>
                    <input type="text" wire:model.defer="name"
                           class="w-full px-3 py-2 border rounded text-sm">
                    @error('name') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>

                <!-- Age & Gender -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-sm font-medium">Age *</label>
                        <input type="number" wire:model.defer="age"
                               class="w-full px-3 py-2 border rounded text-sm">
                        @error('age') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium">Gender *</label>
                        <select wire:model.defer="gender"
                                class="w-full px-3 py-2 border rounded text-sm">
                            <option value="">Select</option>
                            <option>Male</option>
                            <option>Female</option>
                            <option>Other</option>
                        </select>
                        @error('gender') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Parent Phone -->
                <div>
                    <label class="text-sm font-medium">Parent Phone *</label>
                    <input type="text" wire:model.defer="parent_phone"
                           class="w-full px-3 py-2 border rounded text-sm">
                    @error('parent_phone') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>

                <!-- Class -->
                <div>
                    <label class="text-sm font-medium">Class Applying For *</label>

                    <select wire:model.defer="class_applying_for"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors mb-2">
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

                    <input type="text" placeholder="Or enter custom class"
                           wire:model.defer="class_applying_for_custom"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors text-sm mb-1">

                    @error('class_applying_for')
                    <p class="text-red-500 text-xs">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date -->
                <div>
                    <label class="text-sm font-medium">Date of Admission *</label>
                    <input type="date" wire:model.defer="date_of_admission"
                           class="w-full px-3 py-2 border rounded text-sm">
                    @error('date_of_admission') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                </div>

                <!-- Optional Uploads -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div>
                        <label class="text-sm font-medium">Photo (optional)</label>
                        <input type="file" wire:model="image"
                               class="w-full text-sm">
                        @error('image') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium">Transcript (optional)</label>
                        <input type="file" wire:model="transcript"
                               class="w-full text-sm">
                        @error('transcript') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-sm font-medium">Recommendation (optional)</label>
                        <input type="file" wire:model="recommendation_letter"
                               class="w-full text-sm">
                        @error('recommendation_letter') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-2 pt-4">
                    <button
                        type="button"
                        @click="open = false"
                        class="px-4 py-2 text-sm bg-gray-200 rounded hover:bg-gray-300"
                    >
                        Cancel
                    </button>

                    <button
                        type="submit"
                        class="px-4 py-2 text-sm font-semibold bg-[#f84525] text-white rounded
                               hover:bg-red-600 flex items-center gap-2"
                        wire:loading.attr="disabled"
                    >
                        <x-loading-spinner wire:loading />
                        <span wire:loading.remove>Add Student</span>
                        <span wire:loading>Adding…</span>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>



{{-- Edit Student Modal --}}
<div
    x-data="{ open: @entangle('showEditModal') }"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-60 flex items-center justify-center bg-black/60 backdrop-blur-sm px-3 overflow-y-auto"
>

    <!-- Modal Box -->
    <div
        x-show="open"
        x-transition
        class="bg-white w-full max-w-3xl rounded-2xl shadow-2xl p-5 relative mt-10 sm:mt-0 flex flex-col max-h-[85vh] overflow-y-auto"
    >
        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-3 bg-blue-900 text-white rounded-t-2xl">
            <div>
                <h2 class="text-lg font-semibold">Edit Student</h2>
            </div>
            <button
                @click="open = false"
                class="p-2 rounded-full hover:bg-red-500 transition"
            >
                <i class="ri-close-line text-xl"></i>
            </button>
        </div>

        <!-- Spinner (Livewire loading) -->
        <div
            wire:loading.flex
            wire:target="updateStudent"
            class="absolute inset-0 bg-white/70 z-50 items-center justify-center"
        >
            <div class="animate-spin rounded-full h-10 w-10 border-4 border-blue-500 border-t-transparent"></div>
        </div>

        <!-- Body -->
        <div class="p-4 space-y-6">
            <form wire:submit.prevent="updateStudent" class="space-y-4 max-h-[70vh] overflow-y-auto pr-2">
                <!-- Personal Info -->
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <h3 class="font-semibold text-blue-800 mb-3">Personal Information</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="text-sm font-medium">Full Name *</label>
                            <input type="text" wire:model.defer="edit_name"
                                   class="w-full px-3 py-2 border rounded text-sm">
                            @error('edit_name') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium">Age *</label>
                            <input type="number" wire:model.defer="edit_age"
                                   class="w-full px-3 py-2 border rounded text-sm">
                            @error('edit_age') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium">Gender *</label>
                            <select wire:model.defer="edit_gender" class="w-full px-3 py-2 border rounded text-sm">
                                <option value="">Select Gender</option>
                                <option>Male</option>
                                <option>Female</option>
                                <option>Other</option>
                            </select>
                            @error('edit_gender') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium">Parent Phone *</label>
                            <input type="text" wire:model.defer="edit_parent_phone"
                                   class="w-full px-3 py-2 border rounded text-sm">
                            @error('edit_parent_phone') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Academic Info -->
                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                    <h3 class="font-semibold text-green-800 mb-3">Academic Information</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="text-sm font-medium">Class Applying For *</label>
                            <select wire:model.defer="edit_class_applying_for"
                                    class="w-full px-3 py-2 border rounded text-sm">
                                <option value="">Select Class</option>
                                <optgroup label="Nursery">
                                    <option>Nursery 1</option>
                                    <option>Nursery 2</option>
                                    <option>Nursery 3</option>
                                </optgroup>
                                <optgroup label="Primary">
                                    <option>Grade 1</option>
                                    <option>Grade 2</option>
                                    <option>Grade 3</option>
                                    <option>Grade 4</option>
                                    <option>Grade 5</option>
                                    <option>Grade 6</option>
                                </optgroup>
                                <optgroup label="Secondary">
                                    <option>Grade 7</option>
                                    <option>Grade 8</option>
                                    <option>Grade 9</option>
                                    <option>Grade 10</option>
                                    <option>Grade 11</option>
                                    <option>Grade 12</option>
                                </optgroup>
                            </select>
                            @error('edit_class_applying_for') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium">Date of Admission *</label>
                            <input type="date" wire:model.defer="edit_date_of_admission"
                                   class="w-full px-3 py-2 border rounded text-sm">
                            @error('edit_date_of_admission') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Documents -->
                <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                    <h3 class="font-semibold text-purple-800 mb-3">Documents</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="text-sm font-medium">Student Image</label>
                            <input type="file" wire:model="edit_image"
                                   class="w-full text-sm">
                            @error('edit_image') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium">Transcript</label>
                            <input type="file" wire:model="edit_transcript" class="w-full text-sm">
                            @error('edit_transcript') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium">Recommendation Letter</label>
                            <input type="file" wire:model="edit_recommendation_letter" class="w-full text-sm">
                            @error('edit_recommendation_letter') <p class="text-red-500 text-xs">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" @click="open = false"
                            class="px-4 py-2 text-sm bg-gray-200 rounded hover:bg-gray-300">Cancel</button>

                    <button type="submit"
                            class="px-4 py-2 text-sm font-semibold bg-[#f84525] text-white rounded hover:bg-red-600 flex items-center gap-2"
                            wire:loading.attr="disabled">
                        <x-loading-spinner wire:loading />
                        <span wire:loading.remove>Update Student</span>
                        <span wire:loading>Updating…</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- ===============================
     View Student Modal
================================ -->
<div
    x-data="{ open: @entangle('showViewModal') }"
    x-show="open"
    x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm px-3"
>

    <!-- Modal Box -->
    <div
        class="bg-white w-full max-w-3xl rounded-xl shadow-2xl overflow-hidden
               flex flex-col max-h-[85vh]"
    >

        <!-- Header -->
        <!-- Header (Updated to Navy Blue) -->
<div class="flex items-center justify-between px-4 py-3 bg-[#001F54] text-white">
   

            <div>
                <h2 class="text-lg font-semibold">Student Details</h2>
                <p class="text-xs text-blue-100">Profile overview</p>

                {{-- STATUS BADGE (ADDED) --}}
                @if(!empty($view_status))
                    <span
                        class="inline-block mt-1 px-2 py-0.5 rounded-full text-xs font-semibold
                        @if($view_status === 'candidate') bg-yellow-100 text-yellow-800
                        @elseif($view_status === 'active') bg-green-100 text-green-800
                        @else bg-gray-100 text-gray-700 @endif"
                    >
                        {{ ucfirst($view_status) }}
                    </span>
                @endif
            </div>

            <button
                wire:click="$set('showViewModal', false)"
                class="p-2 rounded-full hover:bg-red-500 transition"
            >
                <i class="ri-close-line text-xl"></i>
            </button>
        </div>

        <!-- Spinner (Livewire loading) -->
        <div
            wire:loading.flex
            wire:target="viewStudent"
            class="absolute inset-0 bg-white/70 z-50 items-center justify-center"
        >
            <div class="animate-spin rounded-full h-10 w-10 border-4 border-blue-500 border-t-transparent"></div>
        </div>

        <!-- Body (SCROLLABLE) -->
        <div class="p-4 overflow-y-auto space-y-6">

            <!-- Student Basic Info -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

              <!-- Image (UPDATED: Circular with bold border) -->
<div class="flex justify-center">
    <div class="w-32 h-32 rounded-full border-4 border-blue-700 bg-gray-100 flex items-center justify-center overflow-hidden">
        @if(!empty($view_image))
            <img
                src="{{ asset('storage/'.$view_image) }}"
                class="w-full h-full object-cover"
                alt="Student Photo"
            >
        @else
            <span class="text-gray-400 text-sm">Photo</span>
        @endif
    </div>
</div>


                <!-- Quick Info -->
                <div class="space-y-2 text-sm">
                    <p><span class="font-semibold">Name:</span> {{ $view_name ?? '—' }}</p>
                    <p><span class="font-semibold">Age:</span> {{ $view_age ?? '—' }}</p>
                    <p><span class="font-semibold">Gender:</span> {{ $view_gender ?? '—' }}</p>
                    <p><span class="font-semibold">Class:</span> {{ $view_class ?? '—' }}</p>
                    <p><span class="font-semibold">Admission Date:</span> {{ $view_date ?? '—' }}</p>
                </div>
            </div>

            <hr class="border-gray-200">

            <!-- Detailed Info -->
            <div class="space-y-3 text-sm">
                <h3 class="font-semibold text-gray-700">Parent & Contact</h3>
                <p><span class="font-semibold">Parent Phone:</span> {{ $view_parent_phone ?? '—' }}</p>
            </div>

            <!-- Documents (UPDATED) -->
            <div class="space-y-3">
                <h3 class="font-semibold text-gray-700">Documents</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">

                    {{-- Transcript --}}
                    <div class="p-3 border rounded-lg bg-gray-50">
                        <span class="font-semibold">Transcript:</span>
                        @if(!empty($view_transcript))
                            <a
                                href="{{ asset('storage/'.$view_transcript) }}"
                                target="_blank"
                                class="block mt-1 text-blue-600 hover:underline"
                            >
                                View document
                            </a>
                        @else
                            <span class="text-gray-400 block mt-1">Not uploaded</span>
                        @endif
                    </div>

                    {{-- Recommendation --}}
                    <div class="p-3 border rounded-lg bg-gray-50">
                        <span class="font-semibold">Recommendation:</span>
                        @if(!empty($view_recommendation_letter))
                            <a
                                href="{{ asset('storage/'.$view_recommendation_letter) }}"
                                target="_blank"
                                class="block mt-1 text-blue-600 hover:underline"
                            >
                                View document
                            </a>
                        @else
                            <span class="text-gray-400 block mt-1">Not uploaded</span>
                        @endif
                    </div>

                </div>
            </div>

            <!-- ===============================
                 RESERVED FEES / PAYMENTS SECTION
            ================================ -->
            <div class="border rounded-lg p-4 bg-blue-50">
                <h3 class="font-semibold text-blue-700 mb-2">Fees & Payments</h3>
                <p class="text-sm text-gray-500">
                    Payment records will appear here.
                </p>

                <div class="mt-3 p-3 border border-dashed rounded text-center text-gray-400 text-sm">
                    No payment data available
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-4 py-3 bg-gray-50 flex justify-end gap-2">
            <button
                wire:click="$set('showViewModal', false)"
                class="px-4 py-2 text-sm rounded-lg border hover:bg-gray-100"
            >
                Close
            </button>

          <button
    @click="$wire.editStudent({{ $view_student_id }}); $wire.set('showViewModal', false)"
    class="px-4 py-2 text-sm rounded-lg bg-blue-600 text-white hover:bg-blue-700"
>
    Edit
</button>


        </div>

    </div>
</div>


</div>


