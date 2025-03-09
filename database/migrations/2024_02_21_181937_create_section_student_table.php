<?php

use App\Models\Academy\Section;
use App\Models\Academy\Student;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('section_student', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Section::class)->nullable()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignIdFor(Student::class)->nullable()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->json('invoices')->nullable();
            $table->boolean('paid')->default(false);
            $table->string('note')->nullable();
            $table->tinyInteger('sort')->default(0);
			$table->boolean('draft')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('section_student');
    }
};
