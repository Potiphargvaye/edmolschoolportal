@extends('layouts.admin')

@section('content')

<div class="container-fluid px-2 sm:px-4 md:px-6 lg:px-8">

    <div class="card shadow-sm"> 

        <!-- Header: Grade Entry as Green Button -->
        <div class="card-header bg-transparent">
            <button 
                class="bg-[#25D366] text-white px-3 py-2 hover:bg-[#1ebe5d] rounded text-xs font-bold cursor-default">
                Grade Entry - {{ $gradeLevel }} ({{ $academicYear }})
            </button>
        </div>

        <form method="POST" action="{{ route('grades.store') }}"> 
            @csrf

            <input type="hidden" name="academic_year" value="{{ $academicYear }}">
            <input type="hidden" name="grade_level" value="{{ $gradeLevel }}">

            <div class="card-body p-0">

                <!-- Success Message -->
                @if(session('success'))
                <div class="alert alert-success m-3">
                    {{ session('success') }}
                </div>
                @endif

                <!-- Sticky Control Bar -->
                <div class="sticky top-0 z-50 bg-white border-b shadow-sm px-3 py-2 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">

                    <!-- LEFT SIDE: Semester Status -->
                    <div class="text-sm font-semibold flex flex-wrap gap-3">
                        <div>Semester 1:
                            @if($sem1Locked)
                                <span class="text-red-600">🔒 LOCKED</span>
                            @else
                                <span class="text-green-600">OPEN</span>
                            @endif
                        </div>
                        <div>Semester 2:
                            @if($sem2Locked)
                                <span class="text-red-600">🔒 LOCKED</span>
                            @else
                                <span class="text-green-600">OPEN</span>
                            @endif
                        </div>
                    </div>

                    <!-- RIGHT SIDE: Search + Buttons -->
                    <div class="flex flex-wrap items-center gap-2">

                        <!-- Search Input -->
                        <input type="text" id="studentSearch"
                               placeholder="Search student..."
                               class="border rounded px-3 py-1 text-sm w-48 focus:outline-none focus:ring-1 focus:ring-gray-400">

                        <!-- Lock/Unlock Button -->
                        @can('lock & unlock grade submission')
                        <button type="button"
                                class="bg-red-600 text-white px-3 py-1 rounded shadow hover:bg-red-700 text-sm"
                                onclick="document.getElementById('gradeLockModal').classList.remove('hidden')">
                            🔒 Lock / Unlock Semester
                        </button>
                        @endcan

                        <!-- Save Changes Button -->
                        <button type="submit" 
                                class="bg-[#25D366] text-white px-3 py-2 hover:bg-[#1ebe5d] rounded text-xs">
                            Save Changes
                        </button>

                    </div>
                </div>

                <!-- CHANGE #1: responsive scrolling -->
                <div class="overflow-auto max-h-[70vh]">

                    <div id="noStudentMessage"
     class="hidden bg-yellow-100 border border-yellow-400 text-yellow-800 px-4 py-3 rounded mb-3 text-center font-semibold">
    ⚠️ No student found for your search.
    <a href="#" id="resetSearchTop" class="text-blue-600 underline ml-2 font-bold">
        🔄 Reset
    </a>
</div>
                    <!-- CHANGE #2: thicker black borders for entire table -->
                    <table class="min-w-full bg-white border-2 border-black text-center">

                        <thead class="bg-gray-800 text-white sticky top-0 z-40">
                            <tr>
                                <!-- CHANGE #3: sticky student column kept -->
                                <th rowspan="2"
                                    class="p-3 font-medium text-left sticky left-0 bg-gray-800 z-10 min-w-[180px] border-2 border-black">
                                    Student
                                </th>

                                @foreach($subjects as $subject)
                                <!-- CHANGE #4: thicker borders -->
                                <th colspan="11"
                                    class="p-3 font-bold bg-gray-700 border-2 border-black">
                                    Subject: {{ $subject->name }}
                                </th>
                                @endforeach
                            </tr>

                            <tr>
                                @foreach($subjects as $subject)
                                <!-- CHANGE #5: thicker borders -->
                                <th class="p-2 border-2 border-black">P1</th>
                                <th class="p-2 border-2 border-black">P2</th>
                                <th class="p-2 border-2 border-black">P3</th>
                                <th class="p-2 border-2 border-black">1st Exam</th>

                                <!-- CHANGE #6: NAVY BLUE for 1st Avg -->
                                <th class="p-2 border-2 border-black bg-blue-900 text-white">
                                    1st Avg
                                </th>

                                <th class="p-2 border-2 border-black">P4</th>
                                <th class="p-2 border-2 border-black">P5</th>
                                <th class="p-2 border-2 border-black">P6</th>
                                <th class="p-2 border-2 border-black">2nd Exam</th>

                                <!-- CHANGE #7: NAVY BLUE for 2nd Avg -->
                                <th class="p-2 border-2 border-black bg-blue-900 text-white">
                                    2nd Avg
                                </th>

                                <!-- CHANGE #8: GREEN for Year Avg -->
                                <th class="p-2 border-2 border-black bg-green-600 text-white font-semibold">
                                    Year Avg
                                </th>
                                @endforeach
                            </tr>
                        </thead>

                      <tbody class="divide-y divide-gray-200">

    @foreach($students as $student)
    <tr class="studentRow {{ $loop->even ? 'bg-blue-50' : 'bg-white' }}">

        <!-- CHANGE #9: sticky student column -->
        <td class="studentName p-3 font-medium text-left sticky left-0 bg-white z-10 border-2 border-black">
            {{ $student->name }}
        </td>
       

        @foreach($subjects as $subject)
        @php
            $key = $student->id . '-' . $subject->id;
            $grade = $grades[$key] ?? null;
        @endphp

        <!-- PERIODS 1–3 and EXAM 1 -->
        @foreach(['period1','period2','period3','exam1'] as $period)
        <td class="p-1 border-2 border-black">
            <input type="number"
                   name="grades[{{ $student->id }}][{{ $subject->id }}][{{ $period }}]"
                   value="{{ $grade->$period ?? '' }}"
                   class="form-control form-control-sm text-center w-16 score-input
                   @if($sem1Locked || (isset($grade->$period) && !auth()->user()->can('edit student grades')))
                       bg-red-100 text-red-800 cursor-not-allowed
                   @endif"
                   @if($sem1Locked || (isset($grade->$period) && !auth()->user()->can('edit student grades')))
                       readonly
                       title="This grade is locked by admin: you cannot edit it"
                   @endif>
        </td>
        @endforeach

        <!-- 1st Semester Avg -->
        <td class="p-1 border-2 border-black bg-blue-100">
            <input type="text" readonly class="form-control form-control-sm text-center w-16 sem1">
        </td>

        <!-- PERIODS 4–6 and EXAM 2 -->
        @foreach(['period4','period5','period6','exam2'] as $period)
        <td class="p-1 border-2 border-black">
            <input type="number"
                   name="grades[{{ $student->id }}][{{ $subject->id }}][{{ $period }}]"
                   value="{{ $grade->$period ?? '' }}"
                   class="form-control form-control-sm text-center w-16 score-input
                   @if($sem2Locked || (isset($grade->$period) && !auth()->user()->can('edit student grades')))
                       bg-red-100 text-red-800 cursor-not-allowed
                   @endif"
                   @if($sem2Locked || (isset($grade->$period) && !auth()->user()->can('edit student grades')))
                       readonly
                       title="This grade is locked by admin: you cannot edit it"
                   @endif>
        </td>
        @endforeach

        <!-- 2nd Semester Avg -->
        <td class="p-1 border-2 border-black bg-blue-100">
            <input type="text" readonly class="form-control form-control-sm text-center w-16 sem2">
        </td>

        <!-- Year Avg -->
        <td class="p-1 border-2 border-black bg-green-200 font-semibold">
            <input type="text" readonly class="form-control form-control-sm text-center w-16 yearly">
        </td>

        @endforeach
    </tr>
    @endforeach
    <!-- 🔔 Fallback row -->
<tr id="noStudentRow" class="hidden">
    <td colspan="{{ 1 + ($subjects->count() * 11) }}"
        class="p-5 text-center bg-yellow-100 font-semibold">
        ⚠️ No student found for your search.
        <a href="#" id="resetSearch" class="text-blue-600 underline ml-3 font-bold">
            🔄 Reset
        </a>
    </td>
</tr>

</tbody>
                    </table>
                </div>

            </div>

            <!-- Footer Save Button -->
            <div class="card-footer text-end">
                <!-- CHANGE #12: green save button -->
                <button type="submit" 
                        class="bg-[#25D366] text-white px-3 py-2 hover:bg-[#1ebe5d] rounded text-xs">
                    Save Changes
                </button>
            </div>

        </form>
    </div>
</div>

<!-- Grade Lock Modal -->
<div id="gradeLockModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-40 overflow-auto p-4">

    <!-- Modal Container -->
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-auto p-6">

        <h2 class="text-lg font-bold mb-4">Grade Lock Control</h2>

        <form method="POST" action="{{ route('grades.lock') }}">
            @csrf

            <input type="hidden" name="grade_level" value="{{ $gradeLevel }}">
            <input type="hidden" name="academic_year" value="{{ $academicYear }}">

            <div class="mb-4">
                <label class="block font-semibold mb-2">Select Semester</label>
                <select name="semester" class="w-full border rounded p-2">
                    <option value="sem1">Semester 1 (P1–Exam1)</option>
                    <option value="sem2">Semester 2 (P4–Exam2)</option>
                </select>
            </div>

            <div class="flex flex-wrap justify-between gap-2">
                <button type="submit" name="action" value="lock" class="bg-red-600 text-white px-4 py-2 rounded">
                    Lock
                </button>
                <button type="submit" name="action" value="unlock" class="bg-green-600 text-white px-4 py-2 rounded">
                    Unlock
                </button>
                <button type="button"
                        onclick="document.getElementById('gradeLockModal').classList.add('hidden')"
                        class="bg-gray-400 text-white px-4 py-2 rounded">
                    Cancel
                </button>
            </div>

        </form>
    </div>

</div>


<script>

document.querySelectorAll("input[type='number']").forEach(input => {

input.addEventListener("input", function(){

let row = this.closest("tr");
let cells = row.querySelectorAll("td");

for(let i=1;i<cells.length;i+=11){

let p1 = parseFloat(cells[i].querySelector("input").value) || 0;
let p2 = parseFloat(cells[i+1].querySelector("input").value) || 0;
let p3 = parseFloat(cells[i+2].querySelector("input").value) || 0;
let exam1 = parseFloat(cells[i+3].querySelector("input").value) || 0;

let p4 = parseFloat(cells[i+5].querySelector("input").value) || 0;
let p5 = parseFloat(cells[i+6].querySelector("input").value) || 0;
let p6 = parseFloat(cells[i+7].querySelector("input").value) || 0;
let exam2 = parseFloat(cells[i+8].querySelector("input").value) || 0;

let sem1 = (((p1+p2+p3)/3) + exam1) / 2;
let sem2 = (((p4+p5+p6)/3) + exam2) / 2;
let yearly = (sem1 + sem2) / 2;

cells[i+4].querySelector("input").value = sem1.toFixed(2);
cells[i+9].querySelector("input").value = sem2.toFixed(2);
cells[i+10].querySelector("input").value = yearly.toFixed(2);

}

});

});

</script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    const searchInput = document.getElementById('studentSearch');
    const rows = document.querySelectorAll('tbody tr.studentRow');

    const fallbackRow = document.getElementById('noStudentRow');
    const messageBox = document.getElementById('noStudentMessage');

    const resetTop = document.getElementById('resetSearchTop');
    const resetRow = document.getElementById('resetSearch');

    function filterStudents() {

        const filter = searchInput.value.toLowerCase().trim();
        let anyVisible = false;

        rows.forEach(row => {

            const studentName = row.querySelector('.studentName')
                                   .textContent
                                   .toLowerCase()
                                   .trim();

            if(studentName.includes(filter)) {
                row.style.display = '';
                anyVisible = true;
            } else {
                row.style.display = 'none';
            }

        });

        if(anyVisible) {

            fallbackRow.style.display = 'none';
            messageBox.classList.add('hidden');

        } else {

            fallbackRow.style.display = 'table-row';
            messageBox.classList.remove('hidden');

        }
    }

    searchInput.addEventListener('keyup', filterStudents);

    function resetSearch(e) {

        e.preventDefault();
        searchInput.value = '';
        filterStudents();

    }

    resetTop.addEventListener('click', resetSearch);
    resetRow.addEventListener('click', resetSearch);

});
</script>
@endsection