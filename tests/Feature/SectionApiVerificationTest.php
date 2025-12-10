<?php

namespace Tests\Feature;

use App\Enums\SectionFieldEnum;
use App\Models\SectionType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Services\SectionService;

class SectionApiVerificationTest extends TestCase
{
  use RefreshDatabase;

  protected function setUp(): void
  {
    parent::setUp();
  }

  public function test_admin_can_manage_section_types()
  {
    // 1. Create SectionType
    $response = $this->postJson('/api/cms/admin/section-types', [
      'name' => \App\Enums\SectionTypeEnum::text_with_image->value,
      'fields' => [SectionFieldEnum::title->value, SectionFieldEnum::image->value],
    ]);

    $response->assertStatus(200);
    $this->assertDatabaseHas('section_types', ['slug' => 'text-with-image']);
    $sectionTypeId = $response->json('data.section_type.id');

    // 2. Update SectionType
    $response = $this->putJson("/api/cms/admin/section-types/{$sectionTypeId}", [
      'fields' => [SectionFieldEnum::title->value, SectionFieldEnum::image->value, SectionFieldEnum::subtitle->value],
    ]);
    $response->assertStatus(200);

    // 3. Delete SectionType
    $response = $this->deleteJson("/api/cms/admin/section-types/{$sectionTypeId}");
    $response->assertStatus(200);
    $this->assertDatabaseMissing('section_types', ['id' => $sectionTypeId]);
  }

  public function test_admin_can_manage_sections_with_dynamic_types()
  {
    Storage::fake('public');

    // Setup Type
    $type = SectionType::create([
      'name' => 'Feature Card',
      'slug' => 'feature-card',
      'fields' => [SectionFieldEnum::title->value, SectionFieldEnum::image->value],
    ]);

    // 1. Create Section
    $image = UploadedFile::fake()->image('feature.jpg');
    $response = $this->postJson('/api/cms/admin/sections', [
      'type' => 'feature-card',
      'name' => 'My Feature',
      'content' => ['title' => ['en' => 'Feature Title']],
      'image_desktop' => UploadedFile::fake()->image('desktop.jpg'),
      'image_mobile' => UploadedFile::fake()->image('mobile.jpg'),
      'parent_id' => 1, // Dummy parent ID
      'has_relation' => false,
      'has_button' => false,
      'order' => 1,
    ]);

    $response->assertStatus(200);
    $sectionId = $response->json('data.section.id');
    $this->assertDatabaseHas('cms_sections', ['id' => $sectionId]);

    // Verify Media Attached
    $section = \App\Models\CmsSection::find($sectionId);
    $this->assertTrue($section->hasMedia('image_desktop'));

    // 2. Update Section (Change content)
    $response = $this->putJson("/api/cms/admin/sections/{$sectionId}", [
      'type' => 'feature-card',
      'name' => 'My Feature Updated',
      'content' => ['title' => ['en' => 'Updated Title']],
      // Not sending image, should keep existing
    ]);
    $response->assertStatus(200);

    // 3. Delete Section
    $response = $this->deleteJson("/api/cms/admin/sections/{$sectionId}");
    $response->assertStatus(200);
    $this->assertDatabaseMissing('cms_sections', ['id' => $sectionId]);

    // Verify Media Deleted (Database record should be gone)
    $this->assertDatabaseMissing('media', [
      'model_type' => \App\Models\CmsSection::class,
      'model_id' => $sectionId,
    ]);
  }

  public function test_admin_can_manage_sections_with_buttons()
  {
    // 1. Create SectionType with buttons field
    $response = $this->postJson('/api/cms/admin/section-types', [
      'name' => \App\Enums\SectionTypeEnum::buttons_section->value,
      'fields' => [SectionFieldEnum::title->value, SectionFieldEnum::buttons->value],
    ]);
    $response->assertStatus(200);
    $sectionTypeId = $response->json('data.section_type.id');

    // 2. Create Section with buttons
    $response = $this->postJson('/api/cms/admin/sections', [
      'name' => 'Buttons Section',
      'type' => 'buttons-section',
      'content' => [
        'title' => ['en' => 'Section with Buttons'],
        'buttons' => [
          [
            'label' => ['en' => 'Button 1', 'es' => 'Boton 1', 'ar' => 'Button 1'],
            'url' => 'https://example.com/1',
            'type' => 'primary'
          ],
          [
            'label' => ['en' => 'Button 2', 'es' => 'Boton 2', 'ar' => 'Button 2'],
            'url' => 'https://example.com/2',
            'type' => 'secondary'
          ]
        ]
      ],
      'parent_id' => 1,
      'parent_type' => 'page',
      'has_relation' => false,
      'has_button' => false, // Legacy button field
      'order' => 1,
    ]);

    $response->assertStatus(200);
    $sectionId = $response->json('data.section.id');

    // 3. Verify Data in Database
    $this->assertDatabaseHas('cms_sections', [
      'id' => $sectionId,
      'name' => 'buttons-section', // Slugified
    ]);

    $section = \App\Models\CmsSection::find($sectionId);
    $this->assertNotNull($section);
    $this->assertArrayHasKey('buttons', $section->content);
    $this->assertCount(2, $section->content['buttons']);
    $this->assertEquals('Button 1', $section->content['buttons'][0]['label']['en']);
    $this->assertEquals('https://example.com/1', $section->content['buttons'][0]['url']);
    $this->assertEquals('Button 2', $section->content['buttons'][1]['label']['en']);
  }
}
