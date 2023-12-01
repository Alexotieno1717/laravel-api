<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class LikeDislikeController extends Controller
{
    // app/Http/Controllers/LikeDislikeController.php

    public function upvote(Article $article)
    {
        $user = auth()->user();

        // Check if the user has upvoted the article
        if ($user->upvotes->contains($article)) {
            // Remove the upvote
            $user->upvotes()->toggle($article->id);
            return response(['message' => 'Article upvote removed']);
        }

        // Check if the user has downvoted the article
        if ($user->downvotes->contains($article)) {
            // Switch from downvote to upvote
            $user->downvotes()->toggle($article->id);
            $user->upvotes()->attach($article->id, ['type' => 'upvote']);
            return response(['message' => 'Article switched to upvote']);
        }

        // Upvote the article
        $user->upvotes()->toggle($article->id, ['type' => 'upvote']);

        return response(['message' => 'Article upvoted']);
    }

    public function downvote(Article $article)
    {
        $user = auth()->user();

        // Check if the user has downvoted the article
        if ($user->downvotes->contains($article)) {
            // Remove the downvote
            $user->downvotes()->toggle($article->id);
            return response(['message' => 'Article downvote removed']);
        }

        // Check if the user has upvoted the article
        if ($user->upvotes->contains($article)) {
            // Switch from upvote to downvote
            $user->upvotes()->toggle($article->id);
            $user->downvotes()->attach($article->id, ['type' => 'downvote']);
            return response(['message' => 'Article switched to downvote']);
        }

        // Downvote the article
        $user->downvotes()->toggle($article->id, ['type' => 'downvote']);

        return response(['message' => 'Article downvoted']);
    }


}
