<?php

namespace Database\Seeders;

use App\Enums\GenderEnum;
use App\Models\Academy\Teacher;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        DB::unprepared(File::get(database_path('sql/teachers.sql')));
    }
}
