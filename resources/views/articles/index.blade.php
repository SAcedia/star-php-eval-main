@extends('layouts.app')

@section('title', 'Articles — Star News')

@section('content')
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h1 class="h3 mb-0">Latest articles</h1>
        <div class="col-12 col-md-5 col-lg-4">
            <input type="search" id="article-search" class="form-control"
                   placeholder="Search articles by title&hellip;" autocomplete="off" autofocus>
        </div>
    </div>

    <p id="search-count" class="text-muted small mb-3">Showing {{ $articles->count() }} articles</p>

    <div class="list-group shadow-sm" id="article-list">
        @forelse($articles as $article)
            <a href="{{ route('articles.show', $article) }}"
               class="list-group-item list-group-item-action"
               data-article-title="{{ mb_strtolower($article->title) }}">
                <div class="d-flex w-100 justify-content-between align-items-baseline gap-2">
                    <h5 class="mb-1">{{ $article->title }}</h5>
                    <small class="text-nowrap text-muted">
                        @if ($article->published_at)
                            {{ $article->published_at->format('M j, Y') }}
                        @elseif ($article->drafted_at)
                            Drafted {{ $article->drafted_at->format('M j, Y') }}
                        @else
                            Draft
                        @endif
                    </small>
                </div>
                <p class="mb-1 text-body-secondary">{{ $article->summary }}</p>
                <small>
                    <span class="badge text-bg-primary">{{ $article->category->name }}</span>
                    <span class="badge text-bg-light border">{{ ucfirst($article->status) }}</span>
                    @if ($article->author)
                        <span class="text-muted ms-1">by {{ $article->author }}</span>
                    @endif
                </small>
            </a>
        @empty
            <div class="list-group-item text-center text-muted py-5">No articles yet.</div>
        @endforelse
    </div>

    <div id="search-empty" class="d-none alert alert-light border text-center mt-3">
        No articles match your search.
    </div>

    <script>
        const searchInput = document.getElementById('article-search');
        const articleItems = Array.from(document.querySelectorAll('#article-list [data-article-title]'));
        const emptyState = document.getElementById('search-empty');
        const countLabel = document.getElementById('search-count');
        const total = articleItems.length;

        function updateCount(visible) {
            countLabel.textContent = visible === total
                ? `Showing ${total} article${total === 1 ? '' : 's'}`
                : `Showing ${visible} of ${total} articles`;
        }

        searchInput.addEventListener('input', () => {
            const query = searchInput.value.trim().toLowerCase();
            let visible = 0;

            for (const item of articleItems) {
                const matches = item.dataset.articleTitle.includes(query);
                item.classList.toggle('d-none', !matches);
                if (matches) {
                    visible++;
                }
            }

            emptyState.classList.toggle('d-none', visible > 0);
            updateCount(visible);
        });

        updateCount(total);
    </script>
@endsection
