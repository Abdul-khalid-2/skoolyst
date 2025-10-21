<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Feature;
use App\Models\Curriculum;

class FeatureCurriculumSeeder extends Seeder
{
    public function run()
    {
        // Seed Features
        $features = [
            [
                'name' => 'Science Labs',
                'category' => 'academic',
                'icon' => 'flask',
                'description' => 'Well-equipped science laboratories for physics, chemistry, and biology'
            ],
            [
                'name' => 'Olympic Pool',
                'category' => 'sports',
                'icon' => 'water',
                'description' => 'Olympic-sized swimming pool for training and competitions'
            ],
            [
                'name' => 'Robotics Club',
                'category' => 'academic',
                'icon' => 'robot',
                'description' => 'Robotics and AI club with modern equipment'
            ],
            [
                'name' => 'Music Academy',
                'category' => 'arts',
                'icon' => 'music',
                'description' => 'Comprehensive music education with various instruments'
            ],
            [
                'name' => 'Computer Lab',
                'category' => 'academic',
                'icon' => 'computer',
                'description' => 'Modern computer lab with latest technology'
            ],
            [
                'name' => 'Library',
                'category' => 'academic',
                'icon' => 'library',
                'description' => 'Well-stocked library with digital resources'
            ],
            [
                'name' => 'Sports Ground',
                'category' => 'sports',
                'icon' => 'sports',
                'description' => 'Large playground for various sports activities'
            ],
            [
                'name' => 'Art Studio',
                'category' => 'arts',
                'icon' => 'palette',
                'description' => 'Creative space for art and craft activities'
            ],
            [
                'name' => 'Dance Studio',
                'category' => 'arts',
                'icon' => 'dance',
                'description' => 'Professional dance studio with mirrored walls'
            ],
            [
                'name' => 'Cafeteria',
                'category' => 'facilities',
                'icon' => 'utensils',
                'description' => 'Hygienic cafeteria serving nutritious meals'
            ],
            [
                'name' => 'Auditorium',
                'category' => 'facilities',
                'icon' => 'theater',
                'description' => 'Large auditorium for events and performances'
            ],
            [
                'name' => 'Transportation',
                'category' => 'facilities',
                'icon' => 'bus',
                'description' => 'School bus service for students'
            ],
            [
                'name' => 'Medical Room',
                'category' => 'facilities',
                'icon' => 'medical',
                'description' => 'Fully equipped medical room with nurse'
            ],
            [
                'name' => 'CCTV Surveillance',
                'category' => 'safety',
                'icon' => 'camera',
                'description' => '24/7 CCTV surveillance for campus security'
            ],
            [
                'name' => 'Smart Classes',
                'category' => 'academic',
                'icon' => 'smart-class',
                'description' => 'Digital smart classrooms with interactive boards'
            ],
        ];

        foreach ($features as $feature) {
            Feature::create($feature);
        }

        $this->command->info('Features seeded successfully!');

        // Seed Curriculums
        $curriculums = [
            [
                'name' => 'CBSE',
                'code' => 'cbse',
                'description' => 'Central Board of Secondary Education - National level board'
            ],
            [
                'name' => 'ICSE',
                'code' => 'icse',
                'description' => 'Indian Certificate of Secondary Education'
            ],
            [
                'name' => 'State Board',
                'code' => 'state_board',
                'description' => 'State Government Education Board'
            ],
            [
                'name' => 'IB',
                'code' => 'ib',
                'description' => 'International Baccalaureate - International curriculum'
            ],
            [
                'name' => 'IGCSE',
                'code' => 'igcse',
                'description' => 'International General Certificate of Secondary Education'
            ],
            [
                'name' => 'Cambridge',
                'code' => 'cambridge',
                'description' => 'Cambridge International Curriculum'
            ],
            [
                'name' => 'Montessori',
                'code' => 'montessori',
                'description' => 'Montessori Education System'
            ],
            [
                'name' => 'NIOS',
                'code' => 'nios',
                'description' => 'National Institute of Open Schooling'
            ],
        ];

        foreach ($curriculums as $curriculum) {
            Curriculum::create($curriculum);
        }

        $this->command->info('Curriculums seeded successfully!');
        $this->command->info('Total Features: ' . Feature::count());
        $this->command->info('Total Curriculums: ' . Curriculum::count());
    }
}
