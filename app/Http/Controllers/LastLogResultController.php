<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Nilai;
use App\Models\Question;
use App\Models\User;


class LastLogResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($userId, $categoryId)
    {
        $user = User::find($userId);
        $category = Category::find($categoryId);

        $questionses = Question::where('category_id', $categoryId)->get();

        $attempts = Attempt::where('user_id', $user->id)
            ->where('category_id', $categoryId)
            ->get()
            ->map(function ($attempt) {
                $attempt->nilai = $attempt->is_correct == 1 ? 10 : 0;
                return $attempt;
            });
        // ->map(function ($attempt) {
        //     $attempt->is_true = $attempt->is_true == 1 ? 10 : 0;
        //     return $attempt;
        // });

        $questions = Question::where('category_id', $categoryId)
            ->get()
            ->map(function ($question) use ($attempts) {
                $question->attempt = $attempts->firstWhere('question_id', $question->id);
                return $question;
            });

        $totalAttempts = Attempt::where('user_id', $user->id)
            ->where('category_id', $categoryId)
            ->count();

        $totalCorrectAnswers = Nilai::where('user_id', $user->id)
            ->where('category_id', $categoryId)
            ->where('nilai', 10)
            ->count();

        $totalWrongAnswers = Nilai::where('user_id', $user->id)
            ->where('category_id', $categoryId)
            ->where('nilai', 0)
            ->count();

        return view('backend.pages.logActivity.LastResult', compact('user', 'category', 'questions', 'attempts', 'totalAttempts', 'totalCorrectAnswers', 'totalWrongAnswers'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Your code here
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Your code here
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Your code here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Your code here
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Your code here
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Your code here
    }
}
