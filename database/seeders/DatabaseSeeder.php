<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,
            ScheduleSeeder::class,

            SettingSeeder::class,
            AcademySectionSeeder::class,
            CourseSeeder::class,
            TeacherSeeder::class,
            StudentSeeder::class,

            SectionStudentPaidsSeeder::class,
        ]);
    }
}
