<?php

// app/Http/Controllers/QuestionController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Question;

class QuestionController extends Controller
{
    public function index(Request $request)
    {
        $v = Validator::make($request->all(), [
            'category_id' => ['required','integer','exists:categories,id'],
        ]);

        if ($v->fails()) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors'  => $v->errors(),
            ], 422);
        }

        $categoryId = (int) $request->query('category_id');

        $questions = Question::query()
            ->where('category_id', $categoryId)
            // Optional: uncomment next line to return only active ones
            // ->where('is_active', 1)
            ->orderBy('id')
            ->get(['id','category_id','question','difficulty','is_active','created_at','updated_at']);

        // Option A: return the DB column names as-is (question)
        return response()->json([
            'data' => $questions->map(fn ($q) => [
                'id'          => $q->id,
                'category_id' => $q->category_id,
                'question'    => $q->question,
                'difficulty'  => $q->difficulty,
                'is_active'   => (int) $q->is_active,
                'created_at'  => $q->created_at,
                'updated_at'  => $q->updated_at,
            ])->values(),
        ], 200);
    }
}
