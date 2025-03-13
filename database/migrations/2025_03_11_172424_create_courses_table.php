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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->text('content')->nullable(); 
            $table->string('video_url')->nullable(); 
            $table->integer('duration')->comment('in minutes');
            $table->enum('difficulty_level', ['beginner', 'intermediate', 'advanced']);
            $table->enum('status', ['open', 'in_progress', 'completed']);
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->foreignId('mentor_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};