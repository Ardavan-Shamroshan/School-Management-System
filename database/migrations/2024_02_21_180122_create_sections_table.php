<?php

use App\Models\Academy\Course;
use App\Models\Academy\Teacher;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void {
		Schema::create('sections', function(Blueprint $table) {
			$table->id();
			$table->foreignIdFor(Course::class)->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
			$table->foreignIdFor(Teacher::class)->nullable()->constrained()->cascadeOnUpdate()->nullOnDelete();
			$table->string('name')->nullable();
			$table->string('slug')->nullable()->unique();
			$table->json('schedules')->nullable();
			$table->dateTime('start_date')->nullable();
			$table->dateTime('end_date')->nullable();
			$table->string('start_time')->nullable();
			$table->bigInteger('price')->nullable();
			$table->boolean('status')->default(true);
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void {
		Schema::dropIfExists('sections');
	}
};
