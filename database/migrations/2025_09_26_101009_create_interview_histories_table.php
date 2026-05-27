<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('interview_histories', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $t->string('device_id', 64)->nullable()->index();
            $t->string('role', 80);
            $t->string('topic', 80);
            $t->string('level', 16);
            $t->text('question');
            $t->longText('answer');
            $t->timestamps();
            $t->index(['user_id', 'created_at']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('interview_histories');
    }
};
