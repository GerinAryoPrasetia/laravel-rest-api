<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index()
    {
        return Movie::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'movie_name' => 'required',
            'description' => 'required',
            'release_date' => 'required',
        ]);
        return Movie::create($request->all());
    }

    public function destroy($id)
    {
        return Movie::destroy($id);
    }
}
