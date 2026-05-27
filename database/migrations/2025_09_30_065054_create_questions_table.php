<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('questions', function (Blueprint $t) {
      $t->id();
      $t->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
      $t->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
      $t->string('difficulty', 16)->default('Mid')->index();
      $t->text('question');
      $t->boolean('is_active')->default(true)->index();
      $t->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('questions'); }
};

