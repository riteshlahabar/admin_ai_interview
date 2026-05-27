<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Question;
use App\Models\Answer;
use App\Models\Mark;

class DashboardController extends Controller
{
  public function index(){
    $students = User::where('role','student')->count();
    $questions = Question::count();
    $answers = Answer::count();
    $avgLen = (int) (Answer::selectRaw('AVG(CHAR_LENGTH(answer)) as l')->value('l') ?? 0);
    return view('admin.dashboard', compact('students','questions','answers','avgLen'));
  }
}
