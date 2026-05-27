<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
  public function index(){
    $rows = \App\Models\Question::with(['category','teacher'])->latest()->paginate(20);
    return view('admin.questions.index', compact('rows'));
  }
  public function create(){
    return view('admin.questions.create', [
      'categories'=>\App\Models\Category::orderBy('name')->get(),
      'teachers'=>\App\Models\User::where('role','teacher')->orderBy('name')->get(),
    ]);
  }
  public function store(Request $r){
    $data = $r->validate([
      'teacher_id'=>'required|exists:users,id',
      'category_id'=>'required|exists:categories,id',
      'difficulty'=>'required|in:Junior,Mid,Senior',
      'question'=>'required|string',
      'is_active'=>'nullable|boolean',
    ]);
    \App\Models\Question::create([
      'teacher_id'=>$data['teacher_id'],
      'category_id'=>$data['category_id'],
      'difficulty'=>$data['difficulty'],
      'question'=>$data['question'],
      'is_active'=>$r->boolean('is_active'),
    ]);
    return redirect()->route('admin.questions.index')->with('ok','Question created');
  }
  public function edit(\App\Models\Question $question){
    return view('admin.questions.edit', [
      'q'=>$question->load('category','teacher'),
      'categories'=>\App\Models\Category::orderBy('name')->get(),
      'teachers'=>\App\Models\User::where('role','teacher')->orderBy('name')->get(),
    ]);
  }
  public function update(Request $r, \App\Models\Question $question){
    $data = $r->validate([
      'teacher_id'=>'required|exists:users,id',
      'category_id'=>'required|exists:categories,id',
      'difficulty'=>'required|in:Junior,Mid,Senior',
      'question'=>'required|string',
      'is_active'=>'nullable|boolean',
    ]);
    $question->update([
      'teacher_id'=>$data['teacher_id'],
      'category_id'=>$data['category_id'],
      'difficulty'=>$data['difficulty'],
      'question'=>$data['question'],
      'is_active'=>$r->boolean('is_active'),
    ]);
    return back()->with('ok','Question updated');
  }
  public function destroy(\App\Models\Question $question){
    $question->delete();
    return back()->with('ok','Question deleted');
  }
}

