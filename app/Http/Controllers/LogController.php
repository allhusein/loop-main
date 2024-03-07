<?php

namespace App\Http\Controllers;

use App\Models\Attempt;
use Illuminate\Http\Request;
use App\Models\Log;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    /**
     * Display a listing of the logs.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $logs = Attempt::select('user_id', DB::raw('max(created_at) as max_created_at'))
            ->groupBy('user_id')
            ->get()
            ->map(function ($item) {
                return Attempt::where('user_id', $item->user_id)
                    ->where('created_at', $item->max_created_at)
                    ->first();
            });

        return view('backend.pages.logActivity.index', compact('logs'));
    }

    /**
     * Show the form for creating a new log.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Your code here
    }

    /**
     * Store a newly created log in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Your code here
    }

    /**
     * Display the specified log.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Your code here
    }

    /**
     * Show the form for editing the specified log.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Your code here
    }

    /**
     * Update the specified log in storage.
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
     * Remove the specified log from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Your code here
    }
}
