<?php

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
        Schema::create('subject_matters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('file_path')->nullable();
            $table->string('video_path')->nullable();
            $table->string('video_link')->nullable();
            $table->boolean('is_has_assigment')->default(false);
            $table->text('assigment_content')->nullable();
            $table->dateTime('due_to');
            $table->timestamps();
        });

        Schema::create('subject_matter_has_class', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_matter_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_class_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subject_matters');
        Schema::dropIfExists('subject_matter_has_class');
    }
};
