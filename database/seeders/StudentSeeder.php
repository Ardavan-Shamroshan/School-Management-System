<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class StudentSeeder extends Seeder {
	public function run(): void {
		// DB::unprepared(File::get(database_path('sql/sections.sql')));
		// DB::unprepared(File::get(database_path('sql/students.sql')));
	}
}
