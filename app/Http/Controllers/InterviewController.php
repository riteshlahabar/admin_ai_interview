<?php

namespace App\Http\Controllers;

use App\Exceptions\OpenAIRequestException;
use Illuminate\Http\Request;
use App\Services\InterviewAIService;
use App\Models\InterviewHistory;
use Illuminate\Support\Facades\Log;

class InterviewController extends Controller
{
    public function __construct(private InterviewAIService $svc) {}

    public function ask(Request $r)
    {
        $data = $r->validate([
            'question' => 'required|string|max:400',
            'role' => 'required|string|max:80',
            'topic' => 'required|string|max:80',
            'level' => 'required|string|in:Junior,Mid,Senior',
            'language' => 'nullable|string|max:10',
        ]);

        try {
            $out = $this->svc->generateAnswer(
                $data['question'],
                $data['role'],
                $data['topic'],
                $data['level'],
                $data['language'] ?? 'en'
            );

            InterviewHistory::create([
                'user_id'   => $r->user()->id,
                'device_id' => substr($r->header('X-Device-ID') ?? 'unknown', 0, 64),
                'role'      => $data['role'],
                'topic'     => $data['topic'],
                'level'     => $data['level'],
                'question'  => $data['question'],
                'answer'    => $out['text'],
            ]);

            return response()->json($out);
        } catch (OpenAIRequestException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'error_code' => 'openai_request_failed',
            ], $e->status());
        } catch (\Throwable $e) {
            Log::error('Interview ask failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Unable to process interview request right now.',
                'error_code' => 'interview_request_failed',
            ], 500);
        }
    }

    public function suggestions(Request $r)
    {
        $data = $r->validate([
            'role' => 'required|string|max:80',
            'topic' => 'required|string|max:80',
            'level' => 'required|string|in:Junior,Mid,Senior',
            'language' => 'nullable|string|max:10',
        ]);

        try {
            $list = $this->svc->suggestQuestions(
                $data['role'],
                $data['topic'],
                $data['level'],
                $data['language'] ?? 'en'
            );

            return response()->json(['suggestions' => $list]);
        } catch (OpenAIRequestException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'error_code' => 'openai_request_failed',
            ], $e->status());
        } catch (\Throwable $e) {
            Log::error('Interview suggestion failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Unable to generate suggestions right now.',
                'error_code' => 'suggestion_request_failed',
            ], 500);
        }
    }

    public function history(Request $r)
    {
        $rows = InterviewHistory::query()
            ->where('user_id', $r->user()->id)
            ->orderByDesc('id')
            ->paginate(20, ['id','role','topic','level','question','answer','created_at']);

        return response()->json($rows);
    }
}
