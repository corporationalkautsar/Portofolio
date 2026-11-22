<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class LocaleTest extends TestCase
{
    use RefreshDatabase;

    public function test_language_can_be_switched_between_indonesian_and_english(): void
    {
        $this->post(route('locale.update'), ['locale' => 'id'])->assertRedirect();

        $this->assertSame('id', session('locale'));

        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('locale', 'id')
                ->where('locales', ['en', 'id'])
                ->where('translations.home.heroHeadline', 'MEMBANGUN WEBSITE MODERN.'));

        $this->post(route('locale.update'), ['locale' => 'en'])->assertRedirect();

        $this->get('/')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->where('locale', 'en')
                ->where('translations.home.heroHeadline', 'BUILDING MODERN WEBSITES.'));
    }

    public function test_unsupported_locale_is_rejected(): void
    {
        $this->post(route('locale.update'), ['locale' => 'fr'])
            ->assertSessionHasErrors('locale');
    }
}
