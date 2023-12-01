<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentsController extends Controller
{
    public function store(Request $request, Article $article)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $comment = Auth::user()->comments()->create([
            'content' => $request->input('content'),
            'article_id' => $article->id,
        ]);

        return response(['comment' => $comment], 201);
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $request->validate([
            'content' => 'required|string',
        ]);

        $comment->update([
            'content' => $request->input('content'),
        ]);

        return response(['comment' => $comment]);
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return response(['message' => 'Comment deleted']);
    }
}
