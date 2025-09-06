<?php

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class SubjectSeeder extends Seeder
{
    // Core subjects for all grades
    protected $coreSubjects = [
        'Mathematics',
        'English Language',
        'Science',
        'Social Studies'
    ];
    
    // Additional subjects by grade level
    protected $gradeLevelSubjects = [
        1 => ['Art', 'Music'],
        2 => ['Art', 'Music'],
        3 => ['Art', 'Music'],
        4 => ['Computer Basics', 'Local Language'],
        5 => ['Computer Basics', 'Local Language'],
        6 => ['Computer Studies', 'French'],
        7 => ['Computer Studies', 'French', 'Basic Technology'],
        8 => ['Computer Studies', 'French', 'Basic Technology'],
        9 => ['Physics', 'Chemistry', 'Biology', 'Elective Mathematics'],
        10 => ['Physics', 'Chemistry', 'Biology', 'Elective Mathematics'],
        11 => ['Physics', 'Chemistry', 'Biology', 'Elective Mathematics'],
        12 => ['Physics', 'Chemistry', 'Biology', 'Elective Mathematics']
    ];
    
    // Specialized teacher subjects
    protected $teacherSpecializations = [
        'Mathematics' => ['Mathematics', 'Further Mathematics'],
        'Science' => ['Physics', 'Chemistry', 'Biology'],
        'Languages' => ['English Language', 'French', 'Local Language'],
        'Arts' => ['Art', 'Music'],
        'Technology' => ['Computer Studies', 'Basic Technology'],
        'Social Sciences' => ['Social Studies', 'Geography', 'History']
    ];

    public function run()
    {
        // Seed subjects for grades
        $this->seedGradeSubjects();
        
        // Seed subjects for teachers
        $this->seedTeacherSubjects();
        
        // Seed subjects for students
        $this->seedStudentSubjects();
    }
    
    protected function seedGradeSubjects()
    {
        Grade::all()->each(function ($grade) {
            $subjects = $this->coreSubjects;
            
            if (isset($this->gradeLevelSubjects[$grade->level])) {
                $subjects = array_merge($subjects, $this->gradeLevelSubjects[$grade->level]);
            }
            
            $grade->update(['subjects' => $subjects]);
        });
    }
    
    protected function seedTeacherSubjects()
    {
        $specializationKeys = array_keys($this->teacherSpecializations);
        
        User::where('role', 'teacher')->each(function ($teacher) use ($specializationKeys) {
            // Assign 1-3 subjects based on teacher's specialization
            $specializationKey = $specializationKeys[array_rand($specializationKeys)];
            $specialization = $this->teacherSpecializations[$specializationKey];
            
            // Get random subjects from specialization
            shuffle($specialization);
            $subjects = array_slice($specialization, 0, rand(1, min(3, count($specialization))));
            
            $teacher->update(['subjects' => $subjects]);
        });
    }
    
    protected function seedStudentSubjects()
    {
        User::where('role', 'student')->each(function ($student) {
            if ($student->grade) {
                $coreSubjects = $this->coreSubjects;
                
                // Safely get grade subjects
                $gradeSubjects = $student->grade->subjects ?? [];
                
                // Ensure we have an array
                if (is_string($gradeSubjects)) {
                    $gradeSubjects = json_decode($gradeSubjects, true) ?: [];
                }
                
                // Get elective subjects
                $electives = array_diff($gradeSubjects, $coreSubjects);
                $electives = array_values($electives); // Re-index array
                
                // Select 2-4 electives
                $numElectives = min(4, max(2, count($electives)));
                $selectedElectives = [];
                
                if ($numElectives > 0) {
                    shuffle($electives);
                    $selectedElectives = array_slice($electives, 0, $numElectives);
                }
                
                $subjects = array_merge($coreSubjects, $selectedElectives);
                $student->update(['subjects' => $subjects]);
            }
        });
    }
}