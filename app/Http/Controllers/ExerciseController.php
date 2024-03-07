<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Category;
use App\Models\Exercise;
use App\Models\Question;
use App\Models\Confidence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;

class ExerciseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id_user = auth()->user()->id;
        //insert ke db exercise ketika pertama kali mahasiswa klik category
        $category = Category::with('question')->findOrFail($_GET['id']);
        foreach ($category->question as $q) {
            //cek apakah sudah pernah dibuat
            $exercise = Exercise::where('question_id', $q->id)->where('user_id', $id_user)->first();
            if (!$exercise) {
                $insertExercise = Exercise::insert([
                    'user_id' => $id_user,
                    'category_id' => $_GET['id'],
                    'question_id' => $q->id,
                    'is_true' => 0
                ]);
            }
        }
        //ambil soal pertama yang false dan seterusnya
        $question = Exercise::with(['question' => function ($q) {
            $q->with('answers');
        }])->where('is_true', 0)->where('user_id', $id_user)->where('category_id', $_GET['id'])->first();


        return view('backend.pages.exercise.index', [
            'question' => $question,
            'category' => $category
        ]);
    }
    public function preview()
    {
        $id_user = auth()->user()->id;
        //insert ke db exercise ketika pertama kali mahasiswa klik category

        if (isset($_GET['test'])) {
            $this->exerciseReset();
        }
        $category = Category::with('question')->findOrFail($_GET['id']);
        foreach ($category->question as $q) {
            //cek apakah sudah pernah dibuat
            $exercise = Exercise::where('question_id', $q->id)->where('user_id', $id_user)->first();
            if (!$exercise) {
                $insertExercise = Exercise::insert([
                    'user_id' => $id_user,
                    'category_id' => $_GET['id'],
                    'question_id' => $q->id,
                    'is_true' => 0
                ]);
            }
        }
        //ambil soal pertama yang false dan seterusnya
        $question = Exercise::with(['question' => function ($q) {
            $q->with('answers');
        }])->where('is_true', 0)->where('user_id', $id_user)->where('category_id', $_GET['id'])->first();


        return view('backend.pages.preview.index', [
            'question' => $question,
            'category' => $category
        ]);
    }
    public function exercisePreviewCheck(Request $request)
    {
        //variabel total true ada berapa vs total istrue
        $totalTrue = count($request->input('answer'));
        $totalAnswer = 0;
        foreach ($request->input('answer') as $key => $a) {
            $answer = Answer::where('id', $key)->where('is_true', 1)->first();
            if ($answer)
                $totalAnswer++;
        }

        if ($totalAnswer != $totalTrue) {
            alert::warning('Coba Lagi Ya !', 'Jawabanmu masih ada yang salah nih');
            return redirect()->back();
        }

        $exercise = Exercise::find($request->input('exercise_id'));
        $exercise->update(['is_true' => 1]);




        alert::success('YEAY !', 'Jawaban kamu sudah benar semua');
        return redirect()->route('preview', ['id' => $exercise->category_id]);
    }

    public function exerciseCheck(Request $request)
    {
        //variabel total true ada berapa vs total istrue
        $totalTrue = count($request->input('answer'));
        $totalAnswer = 0;
        foreach ($request->input('answer') as $key => $a) {
            $answer = Answer::where('id', $key)->where('is_true', 1)->first();
            if ($answer)
                $totalAnswer++;
        }

        // Get the exercise
        $exercise = Exercise::find($request->input('exercise_id'));

        // If the answer is correct, update the exercise
        if ($totalAnswer == $totalTrue) {
            $exercise->update(['is_true' => 1]);
            alert::success('YEAY !', 'Jawaban kamu sudah benar semua');
        } else {
            alert::warning('Coba Lagi Ya !', 'Jawabanmu masih ada yang salah nih');
        }

        // Regardless of the answer, record the attempt
        $exercise->attempts()->create([
            'user_id' => auth()->user()->id,
            'is_correct' => $totalAnswer == $totalTrue,
            'confidence' => $request->input('confidence'), // Save the student's confidence level
            'started_at' => $startedAt = Carbon::parse($request->input('started_at')), // this will be the start time
            'finished_at' => $startedAt->copy()->addSeconds($request->input('duration')), // this will be the end time
        ]);

        return redirect()->back();
    }

    public function exerciseReset()
    {
        $id_user = auth()->user()->id;

        $exercise = Exercise::where('user_id', $id_user)->where('category_id', $_GET['id'])->get();

        foreach ($exercise as $e) {
            $e->update(['is_true' => 0]);
        };

        alert::success('Semangat', 'Silahkan jawab ulang latihan ini');
        return redirect()->back();
    }
}
