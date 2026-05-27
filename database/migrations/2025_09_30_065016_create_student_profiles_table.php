<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('student_profiles', function (Blueprint $t) {
      $t->id();
      $t->foreignId('user_id')->constrained('users')->cascadeOnDelete();
      $t->string('phone', 30)->nullable();
      $t->date('dob')->nullable();
      $t->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('student_profiles'); }
};

