<?php

namespace Database\Seeders;

use App\Models\ClientProfile;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClientProfilesTableSeeder extends Seeder
{
    public function run()
    {
        $clients = User::where('role', 'client')->get();

        foreach ($clients as $client) {
            ClientProfile::create([
                'user_id' => $client->id,
                'institution' => $this->getRandomInstitution(),
                'study_level' => $this->getRandomStudyLevel(),
            ]);
        }
    }

    private function getRandomInstitution()
    {
        $institutions = [
            'University of Example', 'State College', 'Technical Institute',
            'Community College', 'Metropolitan University', 'Liberal Arts College',
            'International University', 'Online Academy', 'State University',
            'National College'
        ];
        
        return $institutions[array_rand($institutions)];
    }

    private function getRandomStudyLevel()
    {
        $levels = ['Undergraduate', 'Bachelor', 'Master', 'PhD'];
        return $levels[array_rand($levels)];
    }
}