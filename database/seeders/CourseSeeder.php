<?php

namespace Database\Seeders;

use App\Models\Academy\AcademySection;
use App\Models\Academy\Course;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            ['name' => 'Tiny Talk 1A', 'image' => 'photos/tiny-talk-1a.png'],
            ['name' => 'Tiny Talk 1B', 'image' => 'photos/tiny-talk-1b.png'],
            ['name' => 'Tiny Talk 2A', 'image' => 'photos/tiny-talk-2a.png'],
            ['name' => 'Tiny Talk 2B', 'image' => 'photos/tiny-talk-2b.png'],
            ['name' => 'Tiny Talk 3A', 'image' => 'photos/tiny-talk-3a.png'],
            ['name' => 'Tiny Talk 3B', 'image' => 'photos/tiny-talk-3b.png'],
            ['name' => 'First Friends 1', 'image' => 'photos/first-friends-1.jpg'],
            ['name' => 'First Friends 2', 'image' => 'photos/first-friends-2.jpg'],
            ['name' => 'First Friends 3', 'image' => 'photos/first-friends-3.jpg'],
            ['name' => 'Family And Friends 1', 'image' => 'photos/family-and-friends-1.png'],
            ['name' => 'Family And Friends 2', 'image' => 'photos/family-and-friends-2.png'],
            ['name' => 'Family And Friends 3', 'image' => 'photos/family-and-friends-3.jpg'],
            ['name' => 'Family And Friends 4', 'image' => 'photos/family-and-friends-4.jpg'],
            ['name' => 'Big English 2', 'image' => 'photos/big-english-2.jpg'],
            ['name' => 'Big English 3', 'image' => 'photos/big-english-3.png'],
            ['name' => 'American English File starter', 'image' => 'photos/american-english-file-starter.jpg'],
            ['name' => 'American English File 1', 'image' => 'photos/american-english-file-1.jpg'],
            ['name' => 'American English File 2', 'image' => 'photos/american-english-file-2.jpg'],
            ['name' => 'American English File 3', 'image' => 'photos/american-english-file-3.jpg'],
            ['name' => 'Pockets 2', 'image' => 'photos/pockets-2.png'],
            ['name' => 'Pockets 3', 'image' => 'photos/pockets-3.png'],
            ['name' => 'Speak 1', 'image' => 'photos/speak-1.png'],
            ['name' => 'Speak 2', 'image' => 'photos/speak-2.jpg'],
            ['name' => 'Speak 3', 'image' => 'photos/speak-3.jpg'],
            ['name' => 'TTC'],
        ];

        AcademySection::query()->select('id')->get()
            ->map(function ($academySection) use ($courses) {
                Arr::map($courses, function (array $value, string $key) use ($academySection) {
                    return Course::query()->create($value + ['academy_section_id' => $academySection->id]);
                });
            });
	}
}
