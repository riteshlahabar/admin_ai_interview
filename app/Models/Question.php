<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
  protected $fillable = ['teacher_id','category_id','difficulty','question','is_active'];
  public function category(){ return $this->belongsTo(Category::class); }
  public function teacher(){ return $this->belongsTo(User::class, 'teacher_id'); }
  public function answers(){ return $this->hasMany(Answer::class); }
}
