<?php

namespace App\Http\Controllers;

use App\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    /**
     * return logged in user's todos
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function json(Request $request)
    {
        $todos = $request->user()->todos;
        return response()->json($todos);
    }

    public function index()
    {
        return view('home');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            "name" => ['string','required', 'max:100', 'min:5']
        ]);

        $todo = new Todo();
        $todo->name = $request->input('name');
        $todo->user_id = $request->user()->id;
        $todo->save();
        return response()->json($todo);
    }

    public function update(Request $request, Todo $todo)
    {
        $this->validate($request, [
            "name" => ['string','required', 'max:100', 'min:5']
        ]);

        $todo->name = $request->input('name');
        $todo->save();

        return response()->json($todo);
    }

    public function destroy(Todo $todo)
    {
        $todo->delete();

        return response()->json(null, 204);
    }
}
