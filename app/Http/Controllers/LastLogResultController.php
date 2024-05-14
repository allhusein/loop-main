<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Nilai;
use App\Models\Question;
use App\Models\User;
use App\Models\Timer;
use Carbon\Carbon;



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
            ->with('nilai')
            ->where('category_id', $categoryId)
            ->get()
            ->map(function ($attempt) {
                $attempt->nilai = $attempt->is_correct ? 10 : 0;
                return $attempt;
            });

        $jumlah_baris = Attempt::where('user_id', $user->id)
            ->where('category_id', $categoryId)
            ->count();

        $questions = Question::where('category_id', $categoryId)
            ->get()
            ->map(function ($question) use ($attempts) {
                $question->attempt = $attempts->firstWhere('question_id', $question->id);
                $question->attempt->nilai = $question->attempt->is_correct ? 10 : 0;
                return $question;
            });

        $totalAttempts = Attempt::where('user_id', $user->id)
            ->where('category_id', $categoryId)
            ->count();

        $totalCorrectAnswers = Attempt::where('user_id', $user->id)
            ->where('category_id', $categoryId)
            ->where('is_correct', 1)
            ->count();
        $totalWrongAnswers = Attempt::where('user_id', $user->id)
            ->where('category_id', $categoryId)
            ->where('is_correct', 0)
            ->count();

        $category = Category::where('id', $categoryId)->first();

        $time_limit = $category->time_limit;

        $total_time = $time_limit * $jumlah_baris / 2;

        $total_waktu_pengerjaan = Attempt::where('user_id', $user->id)
            ->where('category_id', $categoryId)
            ->sum('duration');

        return view('backend.pages.logActivity.LastResult', compact('user', 'category', 'questions', 'attempts', 'totalAttempts', 'totalCorrectAnswers', 'totalWrongAnswers', 'questionses', 'total_time', 'total_waktu_pengerjaan'));
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



    public function destroy($user, $category)
    {
        $attempt = Attempt::where('user_id', $user)->where('category_id', $category)->first();
        if ($attempt) {
            $nilai = Nilai::where('attemp_id', $attempt->id)->first();
            if ($nilai) {
                $nilai->delete();
            }
            $attempt->delete();
            return redirect()->back()->with('success', 'Log result deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Log result not found');
        }
    }
}
