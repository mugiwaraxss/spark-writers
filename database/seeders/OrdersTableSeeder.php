<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    public function run()
    {
        $clients = User::where('role', 'client')->pluck('id')->toArray();
        $writers = User::where('role', 'writer')->pluck('id')->toArray();
        
        $statuses = ['pending', 'assigned', 'in_progress', 'completed', 'revision'];
        $subjects = ['English', 'History', 'Business', 'Marketing', 'Psychology', 
                    'Sociology', 'Political Science', 'Economics', 'Computer Science', 
                    'Mathematics'];
        
        // Create 20 orders with different statuses
        for ($i = 1; $i <= 20; $i++) {
            $status = $statuses[array_rand($statuses)];
            $client_id = $clients[array_rand($clients)];
            
            // Only assign writer if status is not pending
            $writer_id = ($status === 'pending') ? null : $writers[array_rand($writers)];
            
            // Set deadline between today and 30 days in the future
            $deadline = Carbon::now()->addDays(rand(1, 30));
            
            Order::create([
                'client_id' => $client_id,
                'writer_id' => $writer_id,
                'title' => "Sample Order #$i: " . $this->getRandomTitle(),
                'description' => "This is a sample order description for order #$i. It contains detailed instructions about what needs to be done.",
                'academic_level' => $this->getRandomAcademicLevel(),
                'subject_area' => $subjects[array_rand($subjects)],
                'word_count' => rand(500, 3000),
                'deadline' => $deadline,
                'status' => $status,
                'price' => rand(30, 300),
            ]);
        }
    }

    private function getRandomTitle()
    {
        $titles = [
            'Analysis of Modern Literature',
            'Impact of Social Media on Business',
            'Climate Change and Global Economy',
            'Healthcare Reform Policies',
            'Artificial Intelligence Ethics',
            'History of Ancient Civilizations',
            'Educational Theories Comparison',
            'Psychology of Consumer Behavior',
            'Marketing Strategies in Digital Age',
            'Political Systems Analysis'
        ];
        
        return $titles[array_rand($titles)];
    }

    private function getRandomAcademicLevel()
    {
        $levels = ['High School', 'College', 'Undergraduate', 'Master', 'PhD'];
        return $levels[array_rand($levels)];
    }
}