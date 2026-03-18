@foreach($students as $index => $student)
    @php
        $grades = $gradesData[$student->id];
        $overallAverage = $overallAverages[$student->id];
        $rank = $ranks[$student->id];
        $totalStudentsCount = $totalStudents;

        $period = explode(',', request()->query('periods', 'yearly'))[$index] ?? 'yearly';
        $showHeader = explode(',', request()->query('headers', '1'))[$index] ?? 1;
        $showFooter = explode(',', request()->query('footers', '1'))[$index] ?? 1;

        // ✅ NEW: handle level
        $levels = explode(',', request()->query('levels', 'senior'));
        $level = $levels[$index] ?? 'senior';

        request()->merge([
            'showHeader' => $showHeader,
            'showFooter' => $showFooter,
            'period' => $period
        ]);
    @endphp

    {{-- ✅ Dynamic blade --}}
    @include("admin.report-cards.$level", [
        'student' => $student,
        'grades' => $grades,
        'overallAverage' => $overallAverage,  
        'rank' => $rank,
        'totalStudents' => $totalStudentsCount
    ])
@endforeach
<style>

@media print{

body{
    margin:0;
}

/* Each student report */
.report-card{
    page-break-after:always;
    padding:5mm;
}

/* Last report should not add blank page */
.report-card:last-child{
    page-break-after:auto;
}

}

</style>











