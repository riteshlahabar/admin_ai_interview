<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnswerController extends Controller
{
  public function index(){
    $rows = \App\Models\Answer::with(['question','teacher'])->latest()->paginate(20);
    return view('admin.answers.index', compact('rows'));
  }
  public function create(){
    return view('admin.answers.create', [
      'questions'=>\App\Models\Question::orderByDesc('id')->get(),
      'teachers'=>\App\Models\User::where('role','teacher')->orderBy('name')->get(),
    ]);
  }
  public function store(Request $r){
    $data = $r->validate([
      'question_id'=>'required|exists:questions,id',
      'teacher_id'=>'required|exists:users,id',
      'answer'=>'required|string',
      'is_approved'=>'nullable|boolean',
    ]);
    \App\Models\Answer::create([
      'question_id'=>$data['question_id'],
      'teacher_id'=>$data['teacher_id'],
      'answer'=>$data['answer'],
      'is_approved'=>$r->boolean('is_approved'),
    ]);
    return redirect()->route('admin.answers.index')->with('ok','Answer created');
  }
  public function edit(\App\Models\Answer $answer){
    return view('admin.answers.edit', [
      'a'=>$answer->load('question','teacher'),
      'questions'=>\App\Models\Question::orderByDesc('id')->get(),
      'teachers'=>\App\Models\User::where('role','teacher')->orderBy('name')->get(),
    ]);
  }
  public function update(Request $r, \App\Models\Answer $answer){
    $data = $r->validate([
      'question_id'=>'required|exists:questions,id',
      'teacher_id'=>'required|exists:users,id',
      'answer'=>'required|string',
      'is_approved'=>'nullable|boolean',
    ]);
    $answer->update([
      'question_id'=>$data['question_id'],
      'teacher_id'=>$data['teacher_id'],
      'answer'=>$data['answer'],
      'is_approved'=>$r->boolean('is_approved'),
    ]);
    return back()->with('ok','Answer updated');
  }
  public function destroy(\App\Models\Answer $answer){
    $answer->delete();
    return back()->with('ok','Answer deleted');
  }
}
