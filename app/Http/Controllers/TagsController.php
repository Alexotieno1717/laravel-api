<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagsController extends Controller
{
    public function index()
    {
        $tags = Tag::all();

        return response(['tags' => $tags]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:tags|max:255',
        ]);

        $tag = Tag::create([
            'name' => $request->input('name'),
        ]);

        return response(['tag' => $tag], 201);
    }
}
