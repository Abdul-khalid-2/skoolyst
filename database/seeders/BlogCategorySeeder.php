<?php
// database/seeders/BlogCategorySeeder.php

namespace Database\Seeders;

use App\Models\BlogCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Core Education Categories
            [
                'name' => 'Teaching Strategies',
                'description' => 'Innovative teaching methods, classroom management, and pedagogical approaches for educators',
                'icon' => 'chalkboard-teacher',
                'is_active' => true,
            ],
            [
                'name' => 'Student Success',
                'description' => 'Study techniques, academic achievement, and personal development for students',
                'icon' => 'user-graduate',
                'is_active' => true,
            ],
            [
                'name' => 'Parent Engagement',
                'description' => 'Building strong school-parent partnerships and family involvement in education',
                'icon' => 'users',
                'is_active' => true,
            ],

            // Technology & Innovation
            [
                'name' => 'EdTech Tools',
                'description' => 'Latest educational technology, digital platforms, and innovative learning solutions',
                'icon' => 'laptop-code',
                'is_active' => true,
            ],
            [
                'name' => 'Digital Learning',
                'description' => 'Online education, virtual classrooms, and remote learning best practices',
                'icon' => 'desktop',
                'is_active' => true,
            ],

            // School Management
            [
                'name' => 'School Leadership',
                'description' => 'Administrative best practices, leadership insights, and school management strategies',
                'icon' => 'school',
                'is_active' => true,
            ],
            [
                'name' => 'Curriculum Development',
                'description' => 'Curriculum planning, instructional design, and educational framework development',
                'icon' => 'book-open',
                'is_active' => true,
            ],

            // Student Development
            [
                'name' => 'Career Guidance',
                'description' => 'Career planning, vocational training, and professional development for students',
                'icon' => 'briefcase',
                'is_active' => true,
            ],
            [
                'name' => 'Student Wellness',
                'description' => 'Mental health, stress management, and overall well-being for students',
                'icon' => 'heart',
                'is_active' => true,
            ],

            // Specialized Education
            [
                'name' => 'STEM Education',
                'description' => 'Science, Technology, Engineering, and Mathematics resources and teaching strategies',
                'icon' => 'atom',
                'is_active' => true,
            ],
            [
                'name' => 'Inclusive Education',
                'description' => 'Special needs education, inclusive classrooms, and supporting diverse learners',
                'icon' => 'universal-access',
                'is_active' => true,
            ],

            // Professional Growth
            [
                'name' => 'Teacher Development',
                'description' => 'Professional growth, workshops, and continuous learning for educators',
                'icon' => 'user-tie',
                'is_active' => true,
            ],

            // Assessment & Evaluation
            [
                'name' => 'Learning Assessment',
                'description' => 'Student evaluation techniques, assessment tools, and progress tracking methods',
                'icon' => 'chart-line',
                'is_active' => true,
            ],

            // Early Education
            [
                'name' => 'Early Childhood',
                'description' => 'Foundational learning, preschool education, and developmental activities',
                'icon' => 'child',
                'is_active' => true,
            ],

            // Education Trends
            [
                'name' => 'Education Trends',
                'description' => 'Latest trends, research, and innovations shaping the future of education',
                'icon' => 'chart-bar',
                'is_active' => true,
            ]
        ];

        foreach ($categories as $category) {
            BlogCategory::create([
                'uuid' => Str::uuid(),
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'icon' => $category['icon'],
                'is_active' => $category['is_active'],
            ]);
        }

        $this->command->info('✅ 15 essential blog categories seeded successfully!');
        $this->command->info('📊 Categories created: ' . count($categories));

        // Display created categories
        $this->command->info('\n📋 Created Categories:');
        foreach ($categories as $index => $category) {
            $this->command->info(($index + 1) . '. ' . $category['name'] . ' - ' . $category['description']);
        }
    }
}
