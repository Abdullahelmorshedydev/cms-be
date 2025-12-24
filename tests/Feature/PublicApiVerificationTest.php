<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\SectionType;
use App\Enums\SectionFieldEnum;
use App\Models\Page;

class PublicApiVerificationTest extends TestCase
{
  use RefreshDatabase;

  public function test_public_can_get_section_types()
  {
    // Create a section type
    SectionType::create([
      'name' => 'Test Type',
      'slug' => 'test-type',
      'fields' => [SectionFieldEnum::TITLE->value],
    ]);

    $response = $this->getJson('/api/cms/sections/types');

    $response->assertStatus(200)
      ->assertJsonStructure(['data' => ['types']]);
  }

  public function test_public_can_get_pages()
  {
    Page::create(['name' => 'Home', 'slug' => 'home']);

    $response = $this->getJson('/api/cms/pages');

    $response->assertStatus(200)
      ->assertJsonStructure(['data' => ['pages']]);
  }

  public function test_public_can_get_sections()
  {
    $response = $this->getJson('/api/cms/sections');

    $response->assertStatus(200)
      ->assertJsonStructure(['data' => ['sections']]);
  }

  public function test_public_can_get_page_section()
  {
    $page = Page::create(['name' => 'Home', 'slug' => 'home']);

    // Create a section for the page
    $sectionType = SectionType::create([
      'name' => 'Test Type',
      'slug' => 'test-type',
      'fields' => [SectionFieldEnum::TITLE->value],
    ]);

    $page->sections()->create([
      'name' => 'hero-section',
      'type' => 'test-type',
      'content' => ['title' => ['en' => 'Hero Title']],
      'parent_type' => Page::class,
      'parent_id' => $page->id,
      'order' => 1,
    ]);

    $response = $this->getJson("/api/cms/pages/{$page->id}/hero-section");

    $response->assertStatus(200)
      ->assertJsonStructure(['data' => ['section']]);
  }
}
