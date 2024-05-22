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
                ->where('category_id', $category->id)
                ->get();

            $category->yakin_benar = $attempts->where('confidence', 'Yakin')
                ->where('is_correct', 1)
                ->count();

            $category->yakin_salah = $attempts->where('confidence', 'Yakin')
                ->where('is_correct', 0)
                ->count();

            $category->tidak_yakin_benar = $attempts->where('confidence', 'Tidak Yakin')
                ->where('is_correct', 1)
                ->count();

            $category->tidak_yakin_salah = $attempts->where('confidence', 'Tidak Yakin')
                ->where('is_correct', 0)
                ->count();

            $category->total_waktu = $attempts->sum('duration');
            return $category;
        })->filter(function ($category) {
            // Only include the category if it has a value
            return $category->nilai > 0;
        });

        return view('backend.pages.confidence.categoryConfidence', compact('user'));
    }

    function calculateFinalScore($datediff)
    {
        $score = 1;
        if ($datediff <= 60) {
            $score = 5;
        } else if ($datediff <= 120) {
            $score = 4;
        } else if ($datediff <= 180) {
            $score = 3;
        } else if ($datediff <= 240) {
            $score = 2;
        }
        return $score;
    }

    public function PerQuestion($userId, $categoryId)
    {
        $user = User::find($userId);
        $category = Category::find($categoryId);

        $totalScores = [
            'yakin_benar' => 0,
            'yakin_salah' => 0,
            'tidak_yakin_benar' => 0,
            'tidak_yakin_salah' => 0,
        ];
        $attempts = Attempt::where('category_id', $categoryId)->where('user_id', $userId)->get();
        foreach ($attempts as $attempt) {
            $attempt->yakin_benar = "-";
            $attempt->yakin_salah = "-";
            $attempt->tidak_yakin_benar = "-";
            $attempt->tidak_yakin_salah = "-";
            $timer_answer = strtotime($attempt->finished_at) - strtotime($attempt->started_at);
            $final_score = $this->calculateFinalScore($timer_answer);
            if ($attempt->confidence == "Yakin" && $attempt->is_correct == 1) {
                $attempt->yakin_benar = $final_score;
                $totalScores['yakin_benar'] += $final_score;
            } else if ($attempt->confidence == "Yakin" && $attempt->is_correct == 0) {
                $attempt->yakin_salah = $final_score;
                $totalScores['yakin_salah'] += $final_score;
            } else if ($attempt->confidence == "Tidak Yakin" && $attempt->is_correct == 1) {
                $attempt->tidak_yakin_benar = $final_score;
                $totalScores['tidak_yakin_benar'] += $final_score;
            } else if ($attempt->confidence == "Tidak Yakin" && $attempt->is_correct == 0) {
                $attempt->tidak_yakin_salah = $final_score;
                $totalScores['tidak_yakin_salah'] += $final_score;
            }
            $attempt->time_taken = date("H:i:s", $timer_answer);
        }

        return view('backend.pages.confidence.categoryPerQuestion', compact('user', 'category', 'attempts', 'totalScores'));
    }


    public function destroy($id)
    {
        $attempt = Attempt::find($id);
        if ($attempt) {
            $attempt->delete();
            return redirect()->back()->with('success', 'Attempt deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Attempt not found');
        }
    }
}
