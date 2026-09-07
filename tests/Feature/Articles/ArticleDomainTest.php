<?php

namespace Tests\Feature\Articles;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleDomainTest extends TestCase
{
    use RefreshDatabase;

    public function test_article_can_be_created_with_bilingual_fields_and_automatic_slugs(): void
    {
        $article = Article::create([
            'title_id' => 'Peresmian Gudang Baru ANS',
            'title_en' => 'ANS New Warehouse Opening',
            'excerpt_id' => 'ANS meresmikan fasilitas gudang baru di Cibubur.',
            'excerpt_en' => 'ANS inaugurates its new warehouse facility in Cibubur.',
            'content_id' => '<p>Konten lengkap peresmian gudang...</p>',
            'content_en' => '<p>Full content of warehouse inauguration...</p>',
            'type' => 'Company Update',
            'published_at' => now()->subHour(),
            'is_active' => true,
            'is_featured' => true,
            'sort_order' => 1,
        ]);

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title_id' => 'Peresmian Gudang Baru ANS',
            'title_en' => 'ANS New Warehouse Opening',
            'slug_id' => 'peresmian-gudang-baru-ans',
            'slug_en' => 'ans-new-warehouse-opening',
            'type' => 'Company Update',
        ]);

        $this->assertEquals('peresmian-gudang-baru-ans', $article->slug_id);
        $this->assertEquals('ans-new-warehouse-opening', $article->slug_en);
    }

    public function test_slug_collision_is_handled_deterministically(): void
    {
        $article1 = Article::create([
            'title_id' => 'Inovasi Laboratorium',
            'title_en' => 'Laboratory Innovation',
        ]);

        $article2 = Article::create([
            'title_id' => 'Inovasi Laboratorium',
            'title_en' => 'Laboratory Innovation',
        ]);

        $article3 = Article::create([
            'title_id' => 'Inovasi Laboratorium',
            'title_en' => 'Laboratory Innovation',
        ]);

        $this->assertEquals('inovasi-laboratorium', $article1->slug_id);
        $this->assertEquals('laboratory-innovation', $article1->slug_en);

        $this->assertEquals('inovasi-laboratorium-2', $article2->slug_id);
        $this->assertEquals('laboratory-innovation-2', $article2->slug_en);

        $this->assertEquals('inovasi-laboratorium-3', $article3->slug_id);
        $this->assertEquals('laboratory-innovation-3', $article3->slug_en);
    }

    public function test_slug_is_stable_and_does_not_change_when_title_is_edited(): void
    {
        $article = Article::create([
            'title_id' => 'Judul Awal Indonesia',
            'title_en' => 'Initial English Title',
        ]);

        $originalSlugId = $article->slug_id;
        $originalSlugEn = $article->slug_en;

        // Edit title
        $article->update([
            'title_id' => 'Judul Baru Indonesia yang Berubah',
            'title_en' => 'Updated English Title That Changed',
        ]);

        $article->refresh();

        // Slugs must remain stable to protect SEO and bookmarks
        $this->assertEquals($originalSlugId, $article->slug_id);
        $this->assertEquals($originalSlugEn, $article->slug_en);
    }

    public function test_localized_accessors_respond_to_active_locale(): void
    {
        $article = Article::create([
            'title_id' => 'Judul Bahasa Indonesia',
            'title_en' => 'English Language Title',
            'excerpt_id' => 'Ringkasan ID',
            'excerpt_en' => 'Summary EN',
            'content_id' => '<p>Konten ID</p>',
            'content_en' => '<p>Content EN</p>',
        ]);

        app()->setLocale('id');
        $this->assertEquals('Judul Bahasa Indonesia', $article->title);
        $this->assertEquals($article->slug_id, $article->slug);
        $this->assertEquals('Ringkasan ID', $article->excerpt);
        $this->assertEquals('<p>Konten ID</p>', $article->content);

        app()->setLocale('en');
        $this->assertEquals('English Language Title', $article->title);
        $this->assertEquals($article->slug_en, $article->slug);
        $this->assertEquals('Summary EN', $article->excerpt);
        $this->assertEquals('<p>Content EN</p>', $article->content);
    }

    public function test_published_scope_strictly_filters_visibility(): void
    {
        // 1. Valid published article
        $validPublished = Article::create([
            'title_id' => 'Artikel Terbit',
            'title_en' => 'Published Article',
            'published_at' => now()->subMinutes(10),
            'is_active' => true,
        ]);

        // 2. Draft article (published_at is null)
        $draftArticle = Article::create([
            'title_id' => 'Artikel Draf',
            'title_en' => 'Draft Article',
            'published_at' => null,
            'is_active' => true,
        ]);

        // 3. Future scheduled article
        $futureArticle = Article::create([
            'title_id' => 'Artikel Masa Depan',
            'title_en' => 'Future Article',
            'published_at' => now()->addDay(),
            'is_active' => true,
        ]);

        // 4. Inactive article with past published_at
        $inactiveArticle = Article::create([
            'title_id' => 'Artikel Nonaktif',
            'title_en' => 'Inactive Article',
            'published_at' => now()->subHour(),
            'is_active' => false,
        ]);

        $publishedArticles = Article::published()->get();

        $this->assertCount(1, $publishedArticles);
        $this->assertTrue($publishedArticles->contains('id', $validPublished->id));
        $this->assertFalse($publishedArticles->contains('id', $draftArticle->id));
        $this->assertFalse($publishedArticles->contains('id', $futureArticle->id));
        $this->assertFalse($publishedArticles->contains('id', $inactiveArticle->id));
    }
}
