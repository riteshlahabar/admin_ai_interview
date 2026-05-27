<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
  protected $fillable = ['user_id','phone','dob'];
  public function user(){ return $this->belongsTo(User::class); }
}
