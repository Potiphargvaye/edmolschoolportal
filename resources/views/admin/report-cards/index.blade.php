@extends('layouts.admin')

@section('content')

<div class="max-w-7xl mx-auto px-4 py-6">


<!-- Header -->
<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">

    <h2 class="text-2xl font-bold text-gray-800">
        📄 Report Card Center
    </h2>

    <!-- Search Box -->
    <div class="w-full md:w-80">
        <input
            type="text"
            id="studentSearch"
            placeholder="Search student name or ID..."
            class="w-full border border-gray-300 rounded-md px-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
        >
    </div>

</div>

<!-- Table Container -->
<div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden">

    <div class="overflow-x-auto">

        <table class="min-w-full text-sm text-left text-gray-700" id="studentsTable">

            <!-- Table Head -->
            <thead class="bg-gray-100 border-b">

                <tr>

                    <th class="px-6 py-3 font-semibold">
                        Student Name
                    </th>

                    <th class="px-6 py-3 font-semibold">
                        Registration ID
                    </th>

                    <th class="px-6 py-3 font-semibold">
                        Grade Level
                    </th>

                    <th class="px-6 py-3 font-semibold text-center">
                        Action
                    </th>

                </tr>

            </thead>

            <!-- Table Body -->
            <tbody class="divide-y divide-gray-200">

                @forelse($students as $student)

                    <tr class="hover:bg-gray-50 transition">

                        <!-- Student Name -->
                        <td class="px-6 py-3 font-medium text-gray-900">
                            {{ $student->name }}
                        </td>

                        <!-- Registration ID -->
                        <td class="px-6 py-3 text-gray-700">
                            {{ $student->student_id ?? 'N/A' }}
                        </td>

                        <!-- Grade Level -->
                        <td class="px-6 py-3 text-gray-700">
                            {{ $student->class_applying_for ?? 'N/A' }}
                        </td>

                        <!-- Print Button -->
                        <td class="px-6 py-3 text-center">

                            <a href="{{ route('report.card.senior', $student->id) }}"
                               target="_blank"
                               class="inline-flex items-center gap-2 bg-blue-600 text-white text-sm font-semibold px-4 py-2 rounded-md hover:bg-blue-700 transition">

                               🖨 Print Report Card

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            No students available.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


</div>

<!-- Search Script -->

<script>

document.getElementById("studentSearch").addEventListener("keyup", function() {

    let filter = this.value.toLowerCase();

    let rows = document.querySelectorAll("#studentsTable tbody tr");

    rows.forEach(function(row) {

        let text = row.innerText.toLowerCase();

        row.style.display = text.includes(filter) ? "" : "none";

    });

});

</script>

@endsection
