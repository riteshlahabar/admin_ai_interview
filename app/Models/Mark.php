<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
  protected $fillable = ['student_id','category_id','exam','score','out_of','exam_date'];
  public function student(){ return $this->belongsTo(User::class, 'student_id'); }
  public function category(){ return $this->belongsTo(Category::class); }
}
