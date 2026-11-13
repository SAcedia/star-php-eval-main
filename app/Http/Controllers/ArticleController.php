<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Contracts\View\View;

class ArticleController extends Controller
{
    /**
     * GET /articles
     *
     * Article list page. Categories are eager loaded to avoid N+1 queries
     * when rendering each article's category badge.
     */
    public function index(): View
    {
        return view('articles.index', [
            'articles' => Article::query()
                ->with('category')
                ->latest('published_at')
                ->get(),
        ]);
    }

    /**
     * GET /articles/{permalink}
     *
     * Article detail page. The route binds the model by the unique
     * permalink column, keeping URLs stable and human readable.
     */
    public function show(Article $article): View
    {
        $article->load('category');

        return view('articles.show', ['article' => $article]);
    }
}
