<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StudentProfile;
use App\Models\Mark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
  public function index(){
    $q = trim((string) request('q', ''));

    $students = User::where('role', 'student')
      ->with('profile')
      ->when($q !== '', function ($query) use ($q) {
        $query->where(function ($search) use ($q) {
          $search->where('name', 'like', "%{$q}%")
            ->orWhere('email', 'like', "%{$q}%");
        });
      })
      ->latest()
      ->paginate(20);

    return view('admin.students.index', compact('students'));
  }

  public function create(){ return view('admin.students.create'); }

  public function store(Request $r){
    $data = $r->validate([
      'name'=>'required|string|max:100',
      'email'=>'required|email|unique:users,email',
      'password'=>'required|string|min:6',
      'phone'=>'nullable|string|max:30',
      'dob'=>'nullable|date',
    ]);
    $user = User::create([
      'name'=>$data['name'], 'email'=>$data['email'],
      'password'=>Hash::make($data['password']), 'role'=>'student'
    ]);
    StudentProfile::create([
      'user_id'=>$user->id, 'phone'=>$data['phone'] ?? null, 'dob'=>$data['dob'] ?? null
    ]);
    return redirect()->route('admin.students.index')->with('ok','Student created');
  }

  public function edit(User $student){
    abort_unless($student->role === 'student', 404);
    $student->load('profile','marks');
    return view('admin.students.edit', compact('student'));
  }

  public function update(Request $r, User $student){
    abort_unless($student->role === 'student', 404);
    $data = $r->validate([
      'name'=>'required|string|max:100',
      'email'=>'required|email|unique:users,email,'.$student->id,
      'password'=>'nullable|string|min:6',
      'phone'=>'nullable|string|max:30',
      'dob'=>'nullable|date',
    ]);
    $student->name = $data['name'];
    $student->email = $data['email'];
    if (!empty($data['password'])) $student->password = Hash::make($data['password']);
    $student->save();

    $student->profile()->updateOrCreate(
      ['user_id'=>$student->id],
      ['phone'=>$data['phone'] ?? null, 'dob'=>$data['dob'] ?? null]
    );

    return back()->with('ok','Student updated');
  }

  public function destroy(User $student){
    abort_unless($student->role === 'student', 404);
    $student->delete();
    return back()->with('ok','Student deleted');
  }
}
