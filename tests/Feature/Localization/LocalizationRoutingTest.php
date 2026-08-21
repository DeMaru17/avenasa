<?php

namespace Tests\Feature\Localization;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocalizationRoutingTest extends TestCase
{
    use RefreshDatabase;

    public function test_root_url_redirects_permanently_to_default_id_locale(): void
    {
        $response = $this->get('/');

        $response->assertStatus(301);
        $response->assertRedirect('/id');
    }

    public function test_indonesian_home_route_is_accessible_and_sets_id_locale(): void
    {
        $response = $this->get('/id');

        $response->assertStatus(200);
        $this->assertSame('id', app()->getLocale());
        $response->assertSee('<html lang="id"', false);
    }

    public function test_english_home_route_is_accessible_and_sets_en_locale(): void
    {
        $response = $this->get('/en');

        $response->assertStatus(200);
        $this->assertSame('en', app()->getLocale());
        $response->assertSee('<html lang="en"', false);
    }

    public function test_unsupported_locale_prefixes_return_404(): void
    {
        $this->get('/fr')->assertStatus(404);
        $this->get('/de')->assertStatus(404);
        $this->get('/ja')->assertStatus(404);
        $this->get('/es/about')->assertStatus(404);
        $this->get('/zh/products')->assertStatus(404);
    }

    public function test_all_standard_public_routes_are_accessible_in_both_locales(): void
    {
        $routes = [
            'about',
            'products.index',
            'partners-clients',
            'contact',
        ];

        foreach ($routes as $routeName) {
            $idResponse = $this->get(route($routeName, ['locale' => 'id']));
            $idResponse->assertStatus(200);
            $idResponse->assertSee('<html lang="id"', false);

            $enResponse = $this->get(route($routeName, ['locale' => 'en']));
            $enResponse->assertStatus(200);
            $enResponse->assertSee('<html lang="en"', false);
        }
    }

    public function test_seo_canonical_and_hreflang_tags_are_rendered_correctly(): void
    {
        $idUrl = url('/id/about');
        $enUrl = url('/en/about');

        $idResponse = $this->get('/id/about');
        $idResponse->assertStatus(200);
        $idResponse->assertSee('<link rel="canonical" href="'.$idUrl.'">', false);
        $idResponse->assertSee('<link rel="alternate" hreflang="id" href="'.$idUrl.'">', false);
        $idResponse->assertSee('<link rel="alternate" hreflang="en" href="'.$enUrl.'">', false);
        $idResponse->assertSee('<link rel="alternate" hreflang="x-default" href="'.$idUrl.'">', false);

        $enResponse = $this->get('/en/about');
        $enResponse->assertStatus(200);
        $enResponse->assertSee('<link rel="canonical" href="'.$enUrl.'">', false);
        $enResponse->assertSee('<link rel="alternate" hreflang="id" href="'.$idUrl.'">', false);
        $enResponse->assertSee('<link rel="alternate" hreflang="en" href="'.$enUrl.'">', false);
        $enResponse->assertSee('<link rel="alternate" hreflang="x-default" href="'.$idUrl.'">', false);
    }
}
