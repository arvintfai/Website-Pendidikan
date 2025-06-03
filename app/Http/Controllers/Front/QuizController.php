<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $access_code = strtoupper($request['kode_akses']);
        $quiz = Quiz::with('questions.options')->where('access_code', $access_code)->where('is_avaible', true)->first();
        if (!$quiz)
            return redirect()->back()->with('error', 'Kode akses tidak ditemukan!');

        return redirect(route('quizShow'))->with('code', $access_code);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {
        $access_code = session('code');
        $quiz = Quiz::with('questions.options')->where('access_code', $access_code)->where('is_avaible', true)->first();
        if (!$access_code)
            return redirect()->route('index')->with('danger', 'Sesi quiz Anda telah habis!');
        return view('front.quiz', ['quiz' => $quiz, 'access_code' => $access_code, 'user_id' => auth()->user()->id]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
