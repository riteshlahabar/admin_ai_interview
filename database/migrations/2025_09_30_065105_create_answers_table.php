<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('answers', function (Blueprint $t) {
      $t->id();
      $t->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
      $t->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
      $t->longText('answer');
      $t->boolean('is_approved')->default(false)->index();
      $t->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('answers'); }
};

