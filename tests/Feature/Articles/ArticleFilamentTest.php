<?php

namespace Tests\Feature\Articles;

use App\Filament\Resources\Articles\Pages\CreateArticle;
use App\Filament\Resources\Articles\Pages\EditArticle;
use App\Filament\Resources\Articles\Pages\ListArticles;
use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

class ArticleFilamentTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        $this->adminUser = User::factory()->create([
            'name' => 'Admin Test',
            'email' => 'admin@avenasa.co.id',
            'password' => Hash::make('password'),
        ]);

        $this->actingAs($this->adminUser);
    }

    public function test_article_resource_list_page_can_be_rendered(): void
    {
        $article = Article::create([
            'title_id' => 'Artikel Uji Filament',
            'title_en' => 'Filament Test Article',
            'type' => 'News',
            'published_at' => now(),
            'is_active' => true,
        ]);

        Livewire::test(ListArticles::class)
            ->assertCanRenderTableColumn('title_id')
            ->assertCanRenderTableColumn('type')
            ->assertCanRenderTableColumn('is_active')
            ->assertSee('Artikel Uji Filament');
    }

    public function test_article_create_form_does_not_contain_slug_fields_and_has_null_published_at_default(): void
    {
        $component = Livewire::test(CreateArticle::class);

        // Verify slug fields do NOT exist on the form (admin never fills slug)
        $component->assertFormFieldDoesNotExist('slug_id');
        $component->assertFormFieldDoesNotExist('slug_en');

        // Verify title fields exist and are required
        $component->assertFormFieldExists('title_id');
        $component->assertFormFieldExists('title_en');

        // Verify published_at defaults to null
        $this->assertNull($component->get('data.published_at'));

        // Verify sort_order defaults automatically to (max + 1)
        $this->assertEquals(1, $component->get('data.sort_order'));
    }

    public function test_article_can_be_created_through_filament_form_and_generates_slug_automatically(): void
    {
        Livewire::test(CreateArticle::class)
            ->fillForm([
                'title_id' => 'Inovasi Teknologi Terkini ANS',
                'title_en' => 'ANS Latest Technology Innovation',
                'excerpt_id' => 'Ringkasan inovasi teknologi terkini',
                'excerpt_en' => 'Summary of latest technology innovation',
                'type' => 'News',
                'is_active' => true,
                'is_featured' => false,
                'sort_order' => 1,
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $this->assertDatabaseHas('articles', [
            'title_id' => 'Inovasi Teknologi Terkini ANS',
            'title_en' => 'ANS Latest Technology Innovation',
            'slug_id' => 'inovasi-teknologi-terkini-ans',
            'slug_en' => 'ans-latest-technology-innovation',
        ]);
    }

    public function test_article_edit_form_can_be_rendered_and_updated_without_modifying_slug(): void
    {
        $article = Article::create([
            'title_id' => 'Judul Awal Diuji',
            'title_en' => 'Initial Tested Title',
            'published_at' => now()->subHour(),
        ]);

        $originalSlugId = $article->slug_id;
        $originalSlugEn = $article->slug_en;

        Livewire::test(EditArticle::class, ['record' => $article->id])
            ->assertFormFieldDoesNotExist('slug_id')
            ->assertFormFieldDoesNotExist('slug_en')
            ->fillForm([
                'title_id' => 'Judul Setelah Diubah di Admin',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $article->refresh();
        $this->assertEquals('Judul Setelah Diubah di Admin', $article->title_id);
        // Slugs must stay intact
        $this->assertEquals($originalSlugId, $article->slug_id);
        $this->assertEquals($originalSlugEn, $article->slug_en);
    }
}
