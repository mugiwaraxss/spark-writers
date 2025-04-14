<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WriterProfile;
use Illuminate\Database\Seeder;

class WriterProfilesTableSeeder extends Seeder
{
    public function run()
    {
        $writers = User::where('role', 'writer')->get();

        foreach ($writers as $index => $writer) {
            $expertiseAreas = $this->getRandomExpertiseAreas();
            
            WriterProfile::create([
                'user_id' => $writer->id,
                'education_level' => $this->getRandomEducationLevel(),
                'bio' => "I am an experienced writer specializing in " . implode(", ", $expertiseAreas) . ".",
                'expertise_areas' => $expertiseAreas,
                'hourly_rate' => rand(15, 50),
                'rating' => rand(35, 50) / 10, // Rating between 3.5 and 5.0
                'availability_status' => rand(0, 5) > 0 ? 'available' : 'busy', // 5/6 chance to be available
            ]);
        }
    }

    private function getRandomEducationLevel()
    {
        $levels = ['Bachelor', 'Master', 'PhD'];
        return $levels[array_rand($levels)];
    }

    private function getRandomExpertiseAreas()
    {
        $allAreas = [
            'English Literature', 'History', 'Business', 'Marketing', 
            'Psychology', 'Sociology', 'Political Science', 'Economics', 
            'Computer Science', 'Mathematics', 'Biology', 'Chemistry', 
            'Physics', 'Engineering', 'Nursing', 'Medicine', 'Law', 
            'Philosophy', 'Education', 'Communication'
        ];
        
        shuffle($allAreas);
        return array_slice($allAreas, 0, rand(2, 5));
    }
}