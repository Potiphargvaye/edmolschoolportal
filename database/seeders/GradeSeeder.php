<?php

namespace Database\Seeders;

use App\Models\Grade;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    public function run()
    {
        $sections = ['A', 'B'];
        
        // Create grades from level 1 to 12 with sections A and B
        foreach (range(1, 12) as $level) {
            foreach ($sections as $section) {
                Grade::create([
                    'level' => $level,
                    'section' => $section,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        
        $this->command->info('Successfully created grades for levels 1-12 with sections A and B!');
    }
}