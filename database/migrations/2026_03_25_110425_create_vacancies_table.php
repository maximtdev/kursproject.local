<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vacancies', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('company', 255);
            $table->string('location', 255);
            $table->string('salary', 50);
            $table->string('type', 50);
            $table->text('description');
            $table->json('requirements');
            $table->enum('status', ['draft', 'published', 'archived'])->default('published');
            $table->foreignId('employer_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vacancies');
    }
};
