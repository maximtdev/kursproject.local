<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vacancy_id')->constrained('vacancies')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name', 50);
            $table->string('surname', 50);
            $table->string('email', 255);
            $table->string('phone', 20);
            $table->text('about');
            $table->string('social_url', 500)->nullable();
            $table->string('resume_url', 500)->nullable();
            $table->enum('status', ['submitted', 'viewed', 'invited', 'rejected'])->default('submitted');
            $table->timestamp('applied_at')->useCurrent();
            $table->timestamps();

            $table->unique(['user_id', 'vacancy_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('responses');
    }
};
