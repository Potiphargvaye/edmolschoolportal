<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicSubject;
use Illuminate\Support\Facades\DB;

class AcademicSubjectSeeder extends Seeder
{
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Reset table before seeding
        DB::table('academic_subjects')->truncate();

        // Enable foreign key checks again
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');




        // KINDERGARTEN SUBJECTS
// KINDERGARTEN SUBJECTS
$kindergartenSubjects = [
    'Bible',
    'English',
    'Reciting Rhymes',
    'Reciting/ID Nos.',
    'Reciting',
    'Math',
    'General Science',
    'Social Studies',
    'Drawing',
    'Writing',
    'Identifying Objs',
    'P.E.',
    'Spelling',
    'Phonics'
];

foreach ($kindergartenSubjects as $subject) {
    AcademicSubject::create([
        'name' => $subject,
        'level' => 'kindergarten'
    ]);
}
     
// ELEMENTARY SUBJECTS
        $elementarySubjects = [
            'Bible',
            'Mathematics',
            'English',
            'Phonics',
            'Reading',
            'Spelling',
            'General Science',
            'Health Science',
            'Social Studies',
            'Computer',
            'Writing',
            'Drawing',
            'P.E.'
        ];

        foreach ($elementarySubjects as $subject) {
            AcademicSubject::create([
                'name' => $subject,
                'level' => 'elementary'
            ]);
        }


        // JUNIOR HIGH SUBJECTS
        $juniorSubjects = [
            'Bible',
            'Mathematics',
            'English Grammar',
            'Phonics',
            'Literature',
            'Vocabulary',
            'General Science',
            'History',
            'Geography',
            'Civics',
            'Computer',
            'P.E.'
        ];

        foreach ($juniorSubjects as $subject) {
            AcademicSubject::create([
                'name' => $subject,
                'level' => 'junior'
            ]);
        }


        // SENIOR HIGH SUBJECTS  
        $seniorSubjects = [
            'Bible',
            'Mathematics',
            'English Lang',
            'Oral English',
            'Literature',
            'Biology',
            'Chemistry',
            'Physics',
            'History',
            'Geography',
            'Government',
            'Economics',
            'Computer',
            'ROTC'
        ];

        foreach ($seniorSubjects as $subject) {
            AcademicSubject::create([
                'name' => $subject,
                'level' => 'senior'
            ]);
        }
    }
}