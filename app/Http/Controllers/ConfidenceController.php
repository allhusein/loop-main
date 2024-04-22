<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Nilai;
use App\Models\Question;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\Attempt;

use Illuminate\Http\Request;

class ConfidenceController extends Controller
{
    public function index()
    {
        $confidences = Attempt::select('user_id', DB::raw('max(created_at) as max_created_at'))
            ->groupBy('user_id')
            ->get()
            ->map(function ($item) {
                return Attempt::where('user_id', $item->user_id)
                    ->where('created_at', $item->max_created_at)
                    ->first();
            });
        return view('backend.pages.confidence.index', compact('confidences'));
    }

    public function show($userId)
    {
        $user = User::find($userId);
        $categories = Category::all();

        $user->categories = $categories->map(function ($category) use ($user) {
            $category->nilai = Nilai::where('user_id', $user->id)
                ->where('category_id', $category->id)
                ->sum('nilai');

            // Calculate the values for the new columns
            $attempts = Attempt::where('user_id', $user->id)
                ->where('category_id', $category->id);

            $category->yakin_benar = $attempts->where('confidence', 'yakin')
                ->where('is_correct', 1)
                ->count();

            $category->yakin_salah = $attempts->where('confidence', 'yakin')
                ->where('is_correct', '!=', 1)
                ->count();

            $category->tidak_yakin_benar = $attempts->where('confidence', '!=', 'yakin')
                ->where('is_correct', 0)
                ->count();

            $category->tidak_yakin_salah = $attempts->where('confidence', '!=', 'yakin')
                ->where('is_correct', '!=', 1)
                ->count();

            return $category;
        });

        return view('backend.pages.confidence.categoryConfidence', compact('user'));
    }


    public function PerQuestion($userId, $categoryId)
    {
        $user = User::find($userId);
        $category = Category::find($categoryId);

        $questions = Question::where('category_id', $categoryId)
            ->with(['attempts' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }])
            ->get();

        foreach ($questions as $question) {
            // Calculate the values for the new columns
            $attempts = Attempt::where('user_id', $user->id)
                ->where('question_id', $question->id);

            $question->yakin_benar = $attempts->where('confidence', 'yakin')
                ->where('is_correct', 1)
                ->count();

            $question->yakin_salah = $attempts->where('confidence', 'yakin')
                ->where('is_correct', '!=', 1)
                ->count();

            $question->tidak_yakin_benar = $attempts->where('confidence', '!=', 'yakin')
                ->where('is_correct', 1)
                ->count();

            $question->tidak_yakin_salah = $attempts->where('confidence', '!=', 'yakin')
                ->where('is_correct', '!=', 1)
                ->count();
        }

        return view('backend.pages.confidence.categoryPerQuestion', compact('user', 'category', 'questions'));
    }
}
