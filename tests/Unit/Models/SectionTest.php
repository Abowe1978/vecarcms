<?php

namespace Tests\Unit\Models;

use App\Models\Section;
use App\Models\User;
use App\Models\SectionImage;
use App\Models\SectionSecretary;
use App\Models\SectionEvent;
use App\Models\SectionHighlight;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SectionTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_should_create_section_with_slug()
    {
        $section = Section::factory()->create([
            'name' => 'Test Section Name'
        ]);

        $this->assertEquals('test-section-name', $section->slug);
    }

    /**
     * @test
     */
    public function it_should_update_slug_when_name_changes()
    {
        $section = Section::factory()->create([
            'name' => 'Original Name'
        ]);

        $section->update(['name' => 'Updated Name']);

        $this->assertEquals('updated-name', $section->slug);
    }

    /**
     * @test
     */
    public function it_should_not_update_slug_when_name_does_not_change()
    {
        $section = Section::factory()->create([
            'name' => 'Test Name'
        ]);

        $originalSlug = $section->slug;
        $section->update(['description' => 'Updated description']);

        $this->assertEquals($originalSlug, $section->slug);
    }

    /**
     * @test
     */
    public function it_should_have_users_relationship()
    {
        $section = Section::factory()->create();
        $users = User::factory()->count(3)->create();
        
        $section->users()->attach($users->pluck('id'));

        $this->assertCount(3, $section->users);
        $this->assertInstanceOf(User::class, $section->users->first());
    }

    /**
     * @test
     */
    public function it_should_have_images_relationship()
    {
        $section = Section::factory()->create();
        SectionImage::factory()->count(3)->create(['section_id' => $section->id]);

        $this->assertCount(3, $section->images);
        $this->assertInstanceOf(SectionImage::class, $section->images->first());
    }

    /**
     * @test
     */
    public function it_should_have_secretaries_relationship()
    {
        $section = Section::factory()->create();
        SectionSecretary::factory()->count(3)->create(['section_id' => $section->id]);

        $this->assertCount(3, $section->secretaries);
        $this->assertInstanceOf(SectionSecretary::class, $section->secretaries->first());
    }

    /**
     * @test
     */
    public function it_should_have_events_relationship()
    {
        $section = Section::factory()->create();
        SectionEvent::factory()->count(3)->create(['section_id' => $section->id]);

        $this->assertCount(3, $section->events);
        $this->assertInstanceOf(SectionEvent::class, $section->events->first());
    }

    /**
     * @test
     */
    public function it_should_have_highlights_relationship()
    {
        $section = Section::factory()->create();
        SectionHighlight::factory()->count(3)->create(['section_id' => $section->id]);

        $this->assertCount(3, $section->highlights);
        $this->assertInstanceOf(SectionHighlight::class, $section->highlights->first());
    }

    /**
     * @test
     */
    public function it_should_have_primary_secretary_relationship()
    {
        $section = Section::factory()->create();
        $primarySecretary = SectionSecretary::factory()->create([
            'section_id' => $section->id,
            'is_primary' => true
        ]);
        SectionSecretary::factory()->create([
            'section_id' => $section->id,
            'is_primary' => false
        ]);

        $this->assertInstanceOf(SectionSecretary::class, $section->primarySecretary);
        $this->assertEquals($primarySecretary->id, $section->primarySecretary->id);
    }

    /**
     * @test
     */
    public function it_should_have_featured_images_relationship()
    {
        $section = Section::factory()->create();
        SectionImage::factory()->create([
            'section_id' => $section->id,
            'is_featured' => true,
            'is_active' => true
        ]);
        SectionImage::factory()->create([
            'section_id' => $section->id,
            'is_featured' => false,
            'is_active' => true
        ]);

        $this->assertCount(1, $section->featuredImages);
        $this->assertTrue($section->featuredImages->first()->is_featured);
    }

    /**
     * @test
     */
    public function it_should_have_upcoming_events_relationship()
    {
        $section = Section::factory()->create();
        SectionEvent::factory()->upcoming()->create([
            'section_id' => $section->id,
            'is_active' => true
        ]);
        SectionEvent::factory()->completed()->create([
            'section_id' => $section->id,
            'is_active' => true
        ]);

        $this->assertCount(1, $section->upcomingEvents);
        $this->assertEquals('upcoming', $section->upcomingEvents->first()->status);
    }

    /**
     * @test
     */
    public function it_should_have_active_highlights_relationship()
    {
        $section = Section::factory()->create();
        SectionHighlight::factory()->active()->valid()->create([
            'section_id' => $section->id
        ]);
        SectionHighlight::factory()->inactive()->create([
            'section_id' => $section->id
        ]);

        $this->assertCount(1, $section->activeHighlights);
        $this->assertTrue($section->activeHighlights->first()->is_active);
    }

    /**
     * @test
     */
    public function it_should_get_full_address_attribute()
    {
        $section = Section::factory()->create([
            'address' => 'Via Roma 123',
            'city' => 'Milano',
            'state' => 'Lombardia',
            'postal_code' => '20100',
            'country' => 'Italy'
        ]);

        $expectedAddress = 'Via Roma 123, Milano, Lombardia, 20100, Italy';
        $this->assertEquals($expectedAddress, $section->full_address);
    }

    /**
     * @test
     */
    public function it_should_get_full_address_with_missing_parts()
    {
        $section = Section::factory()->create([
            'address' => 'Via Roma 123',
            'city' => 'Milano',
            'state' => null,
            'postal_code' => '20100',
            'country' => 'Italy'
        ]);

        $expectedAddress = 'Via Roma 123, Milano, 20100, Italy';
        $this->assertEquals($expectedAddress, $section->full_address);
    }

    /**
     * @test
     */
    public function it_should_get_coordinates_attribute()
    {
        $section = Section::factory()->create([
            'latitude' => 45.4642,
            'longitude' => 9.1900
        ]);

        $expectedCoordinates = [45.4642, 9.1900];
        $this->assertEquals($expectedCoordinates, $section->coordinates);
    }

    /**
     * @test
     */
    public function it_should_return_null_coordinates_when_missing()
    {
        $section = Section::factory()->create([
            'latitude' => null,
            'longitude' => null
        ]);

        $this->assertNull($section->coordinates);
    }

    /**
     * @test
     */
    public function it_should_return_null_coordinates_when_partially_missing()
    {
        $section = Section::factory()->create([
            'latitude' => 45.4642,
            'longitude' => null
        ]);

        $this->assertNull($section->coordinates);
    }

    /**
     * @test
     */
    public function it_should_use_slug_as_route_key()
    {
        $section = Section::factory()->create();

        $this->assertEquals('slug', $section->getRouteKeyName());
    }

    /**
     * @test
     */
    public function it_should_cast_boolean_fields()
    {
        $section = Section::factory()->create([
            'is_active' => true
        ]);

        $this->assertTrue($section->is_active);
        $this->assertIsBool($section->is_active);
    }

    /**
     * @test
     */
    public function it_should_cast_float_fields()
    {
        $section = Section::factory()->create([
            'latitude' => 45.4642,
            'longitude' => 9.1900
        ]);

        $this->assertIsFloat($section->latitude);
        $this->assertIsFloat($section->longitude);
    }

    /**
     * @test
     */
    public function it_should_be_fillable()
    {
        $fillableFields = [
            'name', 'slug', 'description', 'address', 'city', 'state',
            'country', 'postal_code', 'latitude', 'longitude', 'phone',
            'email', 'website', 'is_active'
        ];

        $section = new Section();
        $this->assertEquals($fillableFields, $section->getFillable());
    }

    /**
     * @test
     */
    public function it_should_use_soft_deletes()
    {
        $section = Section::factory()->create();
        $sectionId = $section->id;

        $section->delete();

        $this->assertDatabaseHas('sections', ['id' => $sectionId]);
        $this->assertSoftDeleted('sections', ['id' => $sectionId]);
    }
} 