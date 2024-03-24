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
            return $category;
        });

        return view('backend.pages.confidence.categoryConfidence', compact('user'));
    }
}
