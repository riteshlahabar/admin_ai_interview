<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
  use HasApiTokens, Notifiable;
  protected $fillable = ['name','email','password','role'];

  public function profile(){ return $this->hasOne(StudentProfile::class); }
  public function marks(){ return $this->hasMany(Mark::class, 'student_id'); }
  public function questions(){ return $this->hasMany(Question::class, 'teacher_id'); }
  public function answers(){ return $this->hasMany(Answer::class, 'teacher_id'); }

  public function isAdmin(): bool { return $this->role === 'admin'; }
  public function isTeacher(): bool { return $this->role === 'teacher'; }
  public function isStudent(): bool { return $this->role === 'student'; }
}

