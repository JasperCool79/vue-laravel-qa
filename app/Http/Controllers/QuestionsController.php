<?php

namespace App\Http\Controllers;

use App\User;
use App\Question;
use Gate;
use Illuminate\Http\Request;
use App\Http\Requests\AskQuestionRequest;

class QuestionsController extends Controller
{
    public function __construct(){
        $this->middleware('auth',['except'=>['index','show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $questions = Question::latest()->paginate(5);
        return view('questions.index',compact('questions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $question = new Question();
        return view('questions.create',compact('question'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AskQuestionRequest $request)
    {
        $request->user()->questions()->create($request->only('title','body'));
        return redirect()->route('questions.index')->with('success','Your Question is has been Sumitted');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function show(Question $question)
    {
        $question->increment('views');
        return view('questions.show',compact('question'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question)
    {
        // if(Gate::denies('update-question',$question)){
        //     abort(403,'Access Denied');
        // }
        $this->authorize('update',$question);

        return view('questions.edit',compact('question'));
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function update(AskQuestionRequest $request, Question $question)
    {
        // if(Gate::denies('update-question',$question)){
        //     abort(403,'Access Denied');
        // }

        $this->authorize('update',$question);
        $question->update($request->only('title','body'));
        if(request()->expectsJson()){
            return response()->json([
                'message' => 'Your Question is Updated',
                'body' => $question->body
            ]);
        }
        return redirect()->route('questions.index')->with('success','Your Question is Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Question  $question
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        // if(Gate::denies('delete-question',$question)){
        //     abort(403,'Access Denied');
        // }
        $this->authorize('delete',$question);
        $question->delete();
        if(request()->expectsJson()){
            return response()->json([
                'message' => 'Your Question is Success Delete'
            ]);
        }
        return redirect()->route('questions.index')->with('success','Your Question is Success Delete');
    }
}
