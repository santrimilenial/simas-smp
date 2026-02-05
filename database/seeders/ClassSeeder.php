<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassModel;

class ClassSeeder extends Seeder
{
    public function run(): void
    {
        $classes = [
            // Kelas 7
            ['name' => '7A', 'grade_level' => '7', 'class_group' => 'A', 'order' => 1],
            ['name' => '7B', 'grade_level' => '7', 'class_group' => 'B', 'order' => 2],
            ['name' => '7C', 'grade_level' => '7', 'class_group' => 'C', 'order' => 3],
            ['name' => '7D', 'grade_level' => '7', 'class_group' => 'D', 'order' => 4],
            ['name' => '7E', 'grade_level' => '7', 'class_group' => 'E', 'order' => 5],
            
            // Kelas 8
            ['name' => '8A', 'grade_level' => '8', 'class_group' => 'A', 'order' => 6],
            ['name' => '8B', 'grade_level' => '8', 'class_group' => 'B', 'order' => 7],
            ['name' => '8C', 'grade_level' => '8', 'class_group' => 'C', 'order' => 8],
            ['name' => '8D', 'grade_level' => '8', 'class_group' => 'D', 'order' => 9],
            ['name' => '8E', 'grade_level' => '8', 'class_group' => 'E', 'order' => 10],
            
            // Kelas 9
            ['name' => '9A', 'grade_level' => '9', 'class_group' => 'A', 'order' => 11],
            ['name' => '9B', 'grade_level' => '9', 'class_group' => 'B', 'order' => 12],
            ['name' => '9C', 'grade_level' => '9', 'class_group' => 'C', 'order' => 13],
            ['name' => '9D', 'grade_level' => '9', 'class_group' => 'D', 'order' => 14],
            ['name' => '9E', 'grade_level' => '9', 'class_group' => 'E', 'order' => 15],
            
            // Kelas 10
            ['name' => '10 IPA 1', 'grade_level' => '10', 'class_group' => 'IPA 1', 'order' => 16],
            ['name' => '10 IPA 2', 'grade_level' => '10', 'class_group' => 'IPA 2', 'order' => 17],
            ['name' => '10 IPS 1', 'grade_level' => '10', 'class_group' => 'IPS 1', 'order' => 18],
            ['name' => '10 IPS 2', 'grade_level' => '10', 'class_group' => 'IPS 2', 'order' => 19],
            
            // Kelas 11
            ['name' => '11 IPA 1', 'grade_level' => '11', 'class_group' => 'IPA 1', 'order' => 20],
            ['name' => '11 IPA 2', 'grade_level' => '11', 'class_group' => 'IPA 2', 'order' => 21],
            ['name' => '11 IPS 1', 'grade_level' => '11', 'class_group' => 'IPS 1', 'order' => 22],
            ['name' => '11 IPS 2', 'grade_level' => '11', 'class_group' => 'IPS 2', 'order' => 23],
            
            // Kelas 12
            ['name' => '12 IPA 1', 'grade_level' => '12', 'class_group' => 'IPA 1', 'order' => 24],
            ['name' => '12 IPA 2', 'grade_level' => '12', 'class_group' => 'IPA 2', 'order' => 25],
            ['name' => '12 IPS 1', 'grade_level' => '12', 'class_group' => 'IPS 1', 'order' => 26],
            ['name' => '12 IPS 2', 'grade_level' => '12', 'class_group' => 'IPS 2', 'order' => 27],
        ];

        foreach ($classes as $class) {
            ClassModel::create(array_merge($class, [
                'student_count' => rand(25, 35),
                'is_active' => true,
            ]));
        }
    }
}