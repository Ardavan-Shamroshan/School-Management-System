<?php

namespace Database\Seeders;

use App\Enums\AcademySectionType;
use App\Models\Academy\AcademySection;
use App\Models\Academy\Schedule;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        Schedule::query()->insert([
            ['start_time' => '09:00', 'end_time' => '10:30'],
            ['start_time' => '10:30', 'end_time' => '12:00'],
            ['start_time' => '17:00', 'end_time' => '18:30'],
            ['start_time' => '18:30', 'end_time' => '20:00'],
            ['start_time' => '20:00', 'end_time' => '21:30'],
            ['start_time' => '21:30', 'end_time' => '23:00'],
        ]);
    }
}
