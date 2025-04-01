<?php

namespace Database\Seeders;

use App\Enums\AcademySectionType;
use App\Models\Academy\AcademySection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class AcademySectionSeeder extends Seeder
{
    public function run(): void
    {
        AcademySection::query()->insert([
            ['name' => 'Boys section', 'slug' => 'boys-section', 'type' => AcademySectionType::BOYS, 'image' => 'photos/boy.webp'],
            ['name' => 'Girls section', 'slug' => 'girls-section', 'type' => AcademySectionType::GIRLS, 'image' => 'photos/girl.webp']
        ]);
    }
}
