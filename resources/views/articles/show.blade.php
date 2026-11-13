@extends('layouts.app')

@section('title', $article->title.' — Star News')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <a href="{{ route('articles.index') }}" class="small text-decoration-none">
                &larr; Back to all articles
            </a>

            <article class="card border-0 shadow-sm mt-3">
                <div class="card-body p-4 p-md-5">
                    <div class="mb-2">
                        <span class="badge text-bg-primary">{{ $article->category->name }}</span>
                        <span class="badge text-bg-light border">{{ ucfirst($article->status) }}</span>
                    </div>

                    <h1 class="mb-3">{{ $article->title }}</h1>

                    <p class="text-muted small mb-4">
                        @if ($article->author)
                            By <strong>{{ $article->author }}</strong> &middot;
                        @endif
                        @if ($article->published_at)
                            Published {{ $article->published_at->format('F j, Y \a\t g:i a') }}
                        @elseif ($article->drafted_at)
                            Drafted {{ $article->drafted_at->format('F j, Y \a\t g:i a') }} &middot; not yet published
                        @else
                            <em>Not yet published</em>
                        @endif
                    </p>

                    <p class="lead text-body-secondary">{{ $article->summary }}</p>

                    <div class="article-body">
                        {!! nl2br(e($article->body)) !!}
                    </div>
                </div>
            </article>
        </div>
    </div>
@endsection
