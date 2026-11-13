<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $categories = Category::factory()->count(5)->create();

        $articles = collect()
            ->merge(Article::factory()->count(12)->recycle($categories)->create()) // published (factory default)
            ->merge(Article::factory()->count(5)->draft()->recycle($categories)->create())
            ->merge(Article::factory()->count(3)->archived()->recycle($categories)->create());

        // The id only exists once the row is inserted, so permalinks are
        // finalised here as <title-slug>-<id>, e.g. /articles/aut-fugit-et-autem-5
        $articles->each(
            fn (Article $article) => $article->update([
                'permalink' => Str::slug($article->title).'-'.$article->id,
            ])
        );
    }
}
