<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\AcademicSubject;
use App\Models\StudentGrade;

class ReportCardController extends Controller
{

 public function index($level = 'senior')
    {
        
        // Map level to actual grade levels
        if ($level === 'elementary') {
            $gradeLevels = ['KG','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6'];
        } elseif ($level === 'junior') {
            $gradeLevels = ['Grade 7','Grade 8','Grade 9'];
        } else { // default senior
            $gradeLevels = ['Grade 10','Grade 11','Grade 12'];
            $level = 'senior'; // ensure level is set correctly
        }

        // Get students that have grades in this level
        $students = Student::whereIn( 
            'id',
            StudentGrade::whereIn('grade_level', $gradeLevels)
                ->select('student_id')
                ->distinct()
        )->get();

        return view('admin.report-cards.index', compact('students', 'level'));
    }

public function printSenior($studentId)
{
    // Find the student from the students table
    $student = Student::findOrFail($studentId);

    // Get all grades for the selected student
    $grades = StudentGrade::where('student_id', $studentId)
        ->with('subject') // load subject relationship
        ->orderBy('academic_subject_id') // keep subjects ordered
        ->get();

    /*
    |--------------------------------------------------------------------------
    | Determine the student's grade level and academic year
    |--------------------------------------------------------------------------
    | These values exist in the student_grades table, not the students table.
    | We use the first record since all subjects for the same report card
    | should share the same grade level and academic year.
    */
    $gradeLevel = $grades->first()->grade_level ?? null;
    $academicYear = $grades->first()->academic_year ?? null;


    /*
    |--------------------------------------------------------------------------
    | Get all students who have grades in the SAME grade level and academic year
    |--------------------------------------------------------------------------
    | This ensures ranking only happens within:
    | Example: Grade 12 - Academic Year 2025/2026
    |
    | We use student_grades instead of students table because
    | grade_level and academic_year live in student_grades.
    */
    $classStudents = StudentGrade::where('grade_level', $gradeLevel)
        ->where('academic_year', $academicYear)
        ->select('student_id')
        ->distinct()
        ->get();


    /*
    |--------------------------------------------------------------------------
    | Calculate yearly averages for every student in the class
    |--------------------------------------------------------------------------
    | We'll store them in an array like:
    |
    | [ student_id => overall_average ]
    */
    $averages = [];

    foreach ($classStudents as $cs) {

        // Get all grades for this student in the same class/year
        $sGrades = StudentGrade::where('student_id', $cs->student_id)
            ->where('grade_level', $gradeLevel)
            ->where('academic_year', $academicYear)
            ->get();

        $totalYearAverage = 0;
        $subjectCount = 0;

        foreach ($sGrades as $g) {

            /*
            |--------------------------------------------------------------------------
            | School's Official Formula
            |--------------------------------------------------------------------------
            |
            | First Semester Average:
            | (P1 + P2 + P3 + Exam1) / 2
            |
            | Second Semester Average:
            | (P4 + P5 + P6 + Exam2) / 2
            |
            | Yearly Average:
            | (First Semester Avg + Second Semester Avg) / 2
            */

            $firstSemAvg = ($g->period1 + $g->period2 + $g->period3 + $g->exam1) / 2;

            $secondSemAvg = ($g->period4 + $g->period5 + $g->period6 + $g->exam2) / 2;

            $yearAvg = ($firstSemAvg + $secondSemAvg) / 2;

            $totalYearAverage += $yearAvg;
            $subjectCount++;
        }

        // Calculate the student's overall average across subjects
        if ($subjectCount > 0) {
            $averages[$cs->student_id] = $totalYearAverage / $subjectCount;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Sort students by highest average
    |--------------------------------------------------------------------------
    | arsort() keeps the student IDs as keys but sorts by score descending
    */
    arsort($averages);


    /*
    |--------------------------------------------------------------------------
    | Determine the current student's rank
    |--------------------------------------------------------------------------
    | array_search finds the student's position after sorting
    */
    $rank = array_search($studentId, array_keys($averages)) + 1;


    /*
    |--------------------------------------------------------------------------
    | Count total students in the class
    |--------------------------------------------------------------------------
    */
    $totalStudents = count($averages);


    /*
    |--------------------------------------------------------------------------
    | Send data to the senior report card blade view
    |--------------------------------------------------------------------------
    */
    return view('admin.report-cards.senior', [
        'student' => $student,
        'grades' => $grades,
        'rank' => $rank,
        'totalStudents' => $totalStudents
    ]);
}

}