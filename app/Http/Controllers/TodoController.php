<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreTodoRequest;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $todos = DB::table('todos')->paginate(5);
        return view('todos.index', ['todos' => $todos]);
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
    public function store(StoreTodoRequest $request)
    {
        DB::table('todos')-> insert([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'is_completed' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('todos.index');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $todo = DB::table('todos')->where('id', $id)->first();
        return view('todos.show', ['todo' => $todo]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $todo = DB::table('todos')->where('id', $id)->first();
        return view('todos.edit', ['todo' => $todo]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTodoRequest $request, $id)
    {
        DB::table('todos')
        ->where('id', $id)
        ->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'is_completed' => $request->input('is_completed') ? true : false,
            'updated_at' => now(),
        ]);
        return redirect()->route('todos.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::table('todos')
        ->where('id', $id)
        ->delete();
        return redirect()->route('todos.index');
    }
}
