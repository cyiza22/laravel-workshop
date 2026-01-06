<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('todos.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('todos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return redirect()->route('todos.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($todo)
    {
        return view('todos.show', ['todo' => $todo]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($todo)
    {
        return view('todos.edit', ['todo' => $todo]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $todo)
    {
        return redirect()->route('todos.show', ['todo' => $todo]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($todo)
    {
        return redirect()->route('todos.index');
    }
}
