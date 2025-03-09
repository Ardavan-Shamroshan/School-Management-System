<?php

namespace Database\Seeders;

use App\Enums\AcademySectionType;
use App\Models\Academy\AcademySection;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class AcademySectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Arr::map([
            ['name' => 'Boys section', 'slug' => 'boys-section', 'type' => AcademySectionType::BOYS],
            ['name' => 'Girls section', 'slug' => 'girls-section', 'type' => AcademySectionType::GIRLS]
        ], fn(array $value) => AcademySection::query()->create($value));
    }
}
