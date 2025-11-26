<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questions = Question::orderBy('created_at', 'desc')->paginate(5);
        return view('dashboard.questions.index', compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.questions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        Question::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'is_active' => $request->is_active,
        ]);


        return redirect()->route('questions.index')->with('success', 'Data created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $questions = Question::findOrFail($id);
        return view('dashboard.questions.edit', compact('questions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Question $question)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string|max:255',
            'is_active' => 'required|boolean',
        ]);

        $question->update([
            'question' => $request->question,
            'answer' => $request->answer,
            'is_active' => $request->is_active,
        ]);
        if (!$question) {
            return redirect()->route('questions.index')->with('error', 'Something went wrong, please try again.');
        }

        return redirect()->route('questions.index')->with('success', 'Data updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question)
    {
        $question->delete();
        return redirect()->route('questions.index')->with('success', 'Data deleted successfully');
    }
}
