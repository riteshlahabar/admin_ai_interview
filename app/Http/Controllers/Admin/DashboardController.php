<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Question;
use App\Models\Answer;
use App\Models\InterviewHistory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $students = User::where('role', 'student')->count();
        $questions = Question::count();
        $answers = Answer::count();
        $avgLen = (int) (Answer::selectRaw('AVG(CHAR_LENGTH(answer)) as l')->value('l') ?? 0);

        $interviews = InterviewHistory::count();
        $todayInterviews = InterviewHistory::whereDate('created_at', Carbon::today())->count();
        $topTopic = InterviewHistory::select('topic', DB::raw('COUNT(*) as total'))
            ->groupBy('topic')
            ->orderByDesc('total')
            ->first();
        $topRole = InterviewHistory::select('role', DB::raw('COUNT(*) as total'))
            ->groupBy('role')
            ->orderByDesc('total')
            ->first();

        return view('admin.dashboard', [
            'students' => $students,
            'questions' => $questions,
            'answers' => $answers,
            'avgLen' => $avgLen,
            'interviews' => $interviews,
            'todayInterviews' => $todayInterviews,
            'topTopic' => $topTopic?->topic ?? 'N/A',
            'topRole' => $topRole?->role ?? 'N/A',
        ]);
    }
}
