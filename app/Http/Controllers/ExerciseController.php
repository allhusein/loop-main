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
use App\Models\Nilai;
use App\Models\Timer;

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
        $category_id = $_GET['id'];
        $category = Category::with('question')->findOrFail($category_id);
        $question_id = null;

        //ambil time_limit dari tabel categories
        $time_limit = Category::where('id', $category_id)->first()->time_limit;

        $duration = $time_limit * 60 * 1000; // Konversi menit ke milidetik

        $timeExpired = false;
        // Cek apakah startTime ada di localStorage
        $startTime = session('startTime');

        if ($startTime !== null) {
            // Ubah startTime menjadi integer
            $startTime = (int) $startTime;

            // Hitung waktu tersisa
            $remainingTime = $duration - (time() - $startTime);

            // Jika waktu sudah habis
            if ($remainingTime <= 0) {
                $question = null;
                $timeExpired = true;
            }
        }

        foreach ($category->question as $q) {
            $exercise = Exercise::where('question_id', $q->id)->where('user_id', $id_user)->first();
            if (!$exercise) {
                Exercise::insert([
                    'user_id' => $id_user,
                    'category_id' => $category_id,
                    'question_id' => $q->id,
                    'is_true' => 0
                ]);
            }
            $question_id = $q->id;
        }

        $question = Exercise::with(['question' => function ($q) {
            $q->with('answers');
        }])->where('is_true', 0)->where('user_id', $id_user)->where('category_id', $category_id)->first();

        // Calculate the total nilai for the user and category
        $nilai = Nilai::where('user_id', $id_user)
            ->where('category_id', $category_id)
            ->sum('nilai');

        return view('backend.pages.exercise.index', [
            'question' => $question,
            'category' => $category,
            'nilai' => $nilai,
            'duration' => $duration,
            'timeExpired' => $timeExpired,
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

        // If the answer is correct, update the exercise and set nilai to 10
        if ($totalAnswer == $totalTrue) {
            $exercise->update(['is_true' => 1]);
            $nilai = 10;
            alert::success('YEAY !', 'Jawaban kamu sudah benar semua');
        } else {
            $nilai = 0;
            alert::warning('Coba Lagi Ya !', 'Jawabanmu masih ada yang salah nih');
        }

        // Get the start time from the session
        $attempted_at = session('start_time');

        // Check if the start time is null
        if ($attempted_at === null) {
            // If the start time is null, set it to the current time
            $attempted_at = now();
        }

        // Get the current time as the finish time
        $finished_at = now();

        // Now you can calculate the time difference
        $remainingTime = $finished_at->diffInSeconds($attempted_at);
        //jika totalDuration null
        if ($request->input('totalDuration') == null) {
            $remainingTime = 0.2 + $remainingTime;
        } else {
            $remainingTime = $request->input('remainingTime');
        }


        // Now use $attempted_at and $finished_at when creating the model
        $attempt = $exercise->attempts()->create([
            'user_id' => auth()->user()->id,
            'question_id' => $exercise->question_id,
            'category_id' => $exercise->category_id,
            'is_correct' => $totalAnswer == $totalTrue,
            'confidence' => $request->input('confidence'), // Save the student's confidence level
            'attempted_at' => $attempted_at, // Use the attempted_at time
            'started_at' => $attempted_at, // Set started_at to the current time
            'finished_at' => $finished_at, // Set finished_at to the current time
            'duration' => $remainingTime, // Save the time difference

        ]);

        // Now you can calculate the time difference
        $time_difference = $finished_at->diffInSeconds($attempted_at);
        //jika totalDuration null
        if ($request->input('totalDuration') == null) {
            $totalDuration = 10 + $time_difference;
        } else {
            $totalDuration = $request->input('totalDuration');
        }




        // Simpan data waktu yang dihabiskan ke dalam tabel timers
        $timer = new Timer();
        $timer->user_id = auth()->user()->id;
        $timer->question_id = $exercise->question_id;
        $timer->waktu = $totalDuration;
        $timer->save();

        // Create a new entry in the nilais table
        Nilai::create([
            'user_id' => auth()->user()->id,
            'question_id' => $exercise->question_id, // Assuming the exercise has a question_id field
            'category_id' => $exercise->category_id, // Assuming the exercise has a category_id field
            'nilai' => $nilai,
        ]);

        return redirect()->back();
    }


    public function exerciseReset()
    {

        $id_user = auth()->user()->id;

        $exercise = Exercise::where('user_id', $id_user)->where('category_id', $_GET['id'])->get();

        foreach ($exercise as $e) {
            $e->update(['is_true' => 1]);
        };

        // Hapus baris ini
        // alert::success('Semangat', 'Silahkan jawab ulang latihan ini');

        return redirect()->back();
    }

    //exerciseRecovery untuk exercise
    public function exerciseRecovery()
    {
        $id_user = auth()->user()->id;

        $exercise = Exercise::where('user_id', $id_user)->where('category_id', $_GET['id'])->get();

        foreach ($exercise as $e) {
            $e->update(['is_true' => 0]);
        };

        // Hapus baris ini
        // alert::success('Semangat', 'Silahkan jawab ulang latihan ini');

        return redirect()->back();
    }




    // public function calculateScore(Request $request)
    // {
    //     $id_user = auth()->user()->id;
    //     $category_id = $request->input('category_id');

    //     $correctAnswers = Exercise::where('user_id', $id_user)
    //         ->where('category_id', $category_id)
    //         ->where('is_true', 1)
    //         ->get();

    //     $score = $correctAnswers->count() * 10;

    //     Nilai::create([
    //         'user_id' => $id_user,
    //         'category_id' => $category_id,
    //         'nilai' => $score,
    //     ]);

    //     return redirect()->back()->with('success', 'Score calculated successfully');
    // }

    // public function show($id)
    // {
    //     $category = Category::findOrFail($id);
    //     $question = Exercise::where('user_id', auth()->user()->id)
    //         ->where('category_id', $category->id)
    //         ->where('is_true', 0)
    //         ->first();

    //     $nilai = Nilai::where('user_id', auth()->user()->id)
    //         ->where('category_id', $category->id)
    //         ->first();

    //     return view('backend.pages.exercise.index', compact('question', 'category', 'nilai'));
    // }
}
