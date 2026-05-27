<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('marks', function (Blueprint $t) {
      $t->id();
      $t->foreignId('student_id')->constrained('users')->cascadeOnDelete();
      $t->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
      $t->string('exam', 80)->default('Interview Round');
      $t->unsignedInteger('score');
      $t->unsignedInteger('out_of')->default(100);
      $t->date('exam_date')->nullable();
      $t->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('marks'); }
};

