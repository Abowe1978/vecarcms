<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Section;
use App\Models\SectionImage;
use App\Models\SectionSecretary;
use App\Models\SectionEvent;
use App\Models\SectionHighlight;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SectionControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $moderator;
    private Section $section;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
        
        // Create moderator user
        $this->moderator = User::factory()->create();
        $this->moderator->assignRole('moderator');
        $this->moderator->update(['section_id' => 1]);
        
        // Create test section
        $this->section = Section::factory()->create(['id' => 1]);
        
        Storage::fake('public');
    }

    /**
     * @test
     */
    public function admin_can_view_sections_index()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.sections.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.sections.index');
    }

    /**
     * @test
     */
    public function moderator_can_view_own_section()
    {
        $response = $this->actingAs($this->moderator)
            ->get(route('admin.sections.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.sections.index');
    }

    /**
     * @test
     */
    public function admin_can_view_section_create_form()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.sections.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.sections.create');
    }

    /**
     * @test
     */
    public function admin_can_create_section()
    {
        $sectionData = [
            'name' => 'Test Section',
            'description' => 'Test Description',
            'address' => 'Test Address',
            'city' => 'Test City',
            'state' => 'Test State',
            'country' => 'Italy',
            'postal_code' => '12345',
            'phone' => '+39 123 456 789',
            'email' => 'test@example.com',
            'website' => 'https://example.com',
            'is_active' => true
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.sections.store'), $sectionData);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('sections', [
            'name' => 'Test Section',
            'slug' => 'test-section',
            'city' => 'Test City'
        ]);
    }

    /**
     * @test
     */
    public function admin_can_view_section_show_page()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.sections.show', $this->section));

        $response->assertStatus(200);
        $response->assertViewIs('admin.sections.show');
        $response->assertViewHas('section', $this->section);
    }

    /**
     * @test
     */
    public function moderator_can_view_own_section_show_page()
    {
        $response = $this->actingAs($this->moderator)
            ->get(route('admin.sections.show', $this->section));

        $response->assertStatus(200);
        $response->assertViewIs('admin.sections.show');
    }

    /**
     * @test
     */
    public function moderator_cannot_view_other_section_show_page()
    {
        $otherSection = Section::factory()->create();

        $response = $this->actingAs($this->moderator)
            ->get(route('admin.sections.show', $otherSection));

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function admin_can_view_section_edit_form()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.sections.edit', $this->section));

        $response->assertStatus(200);
        $response->assertViewIs('admin.sections.edit');
        $response->assertViewHas('section', $this->section);
    }

    /**
     * @test
     */
    public function admin_can_update_section()
    {
        $updateData = [
            'name' => 'Updated Section',
            'description' => 'Updated Description',
            'city' => 'Updated City',
            'is_active' => false
        ];

        $response = $this->actingAs($this->admin)
            ->put(route('admin.sections.update', $this->section), $updateData);

        $response->assertRedirect(route('admin.sections.show', $this->section));
        $response->assertSessionHas('success');
        
        $this->section->refresh();
        $this->assertEquals('Updated Section', $this->section->name);
        $this->assertEquals('updated-section', $this->section->slug);
        $this->assertFalse($this->section->is_active);
    }

    /**
     * @test
     */
    public function admin_can_delete_section()
    {
        $response = $this->actingAs($this->admin)
            ->delete(route('admin.sections.destroy', $this->section));

        $response->assertRedirect(route('admin.sections.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseMissing('sections', ['id' => $this->section->id]);
    }

    /**
     * @test
     */
    public function admin_can_add_member_to_section()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($this->admin)
            ->post(route('admin.sections.members.add', $this->section), [
                'user_id' => $user->id,
                'joined_at' => '2024-01-01'
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('section_user', [
            'section_id' => $this->section->id,
            'user_id' => $user->id
        ]);
    }

    /**
     * @test
     */
    public function admin_can_remove_member_from_section()
    {
        $user = User::factory()->create();
        $this->section->users()->attach($user->id);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.sections.members.remove', [$this->section, $user]));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseMissing('section_user', [
            'section_id' => $this->section->id,
            'user_id' => $user->id
        ]);
    }

    // ===== SECTION IMAGES TESTS =====

    /**
     * @test
     */
    public function admin_can_view_section_images_index()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.sections.images.index', $this->section));

        $response->assertStatus(200);
        $response->assertViewIs('admin.sections.images.index');
        $response->assertViewHas('section', $this->section);
    }

    /**
     * @test
     */
    public function admin_can_view_section_image_create_form()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.sections.images.create', $this->section));

        $response->assertStatus(200);
        $response->assertViewIs('admin.sections.images.create');
        $response->assertViewHas('section', $this->section);
    }

    /**
     * @test
     */
    public function admin_can_create_section_image()
    {
        $file = UploadedFile::fake()->image('test-image.jpg');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.sections.images.store', $this->section), [
                'title' => 'Test Image',
                'description' => 'Test Description',
                'image' => $file,
                'image_type' => 'gallery',
                'is_featured' => true,
                'is_active' => true
            ]);

        $response->assertRedirect(route('admin.sections.images.index', $this->section));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('section_images', [
            'section_id' => $this->section->id,
            'title' => 'Test Image',
            'image_type' => 'gallery',
            'is_featured' => true,
            'is_active' => true
        ]);
    }

    /**
     * @test
     */
    public function admin_can_view_section_image_edit_form()
    {
        $image = SectionImage::factory()->create(['section_id' => $this->section->id]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.sections.images.edit', [$this->section, $image]));

        $response->assertStatus(200);
        $response->assertViewIs('admin.sections.images.edit');
        $response->assertViewHas('section', $this->section);
        $response->assertViewHas('image', $image);
    }

    /**
     * @test
     */
    public function admin_can_update_section_image()
    {
        $image = SectionImage::factory()->create(['section_id' => $this->section->id]);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.sections.images.update', [$this->section, $image]), [
                'title' => 'Updated Image',
                'description' => 'Updated Description',
                'image_type' => 'banner',
                'is_featured' => false,
                'is_active' => true
            ]);

        $response->assertRedirect(route('admin.sections.images.index', $this->section));
        $response->assertSessionHas('success');
        
        $image->refresh();
        $this->assertEquals('Updated Image', $image->title);
        $this->assertEquals('banner', $image->image_type);
        $this->assertFalse($image->is_featured);
    }

    /**
     * @test
     */
    public function admin_can_delete_section_image()
    {
        $image = SectionImage::factory()->create(['section_id' => $this->section->id]);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.sections.images.destroy', [$this->section, $image]));

        $response->assertRedirect(route('admin.sections.images.index', $this->section));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseMissing('section_images', ['id' => $image->id]);
    }


    // ===== SECTION EVENTS TESTS =====

    /**
     * @test
     */
    public function admin_can_view_section_events_index()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.sections.events.index', $this->section));

        $response->assertStatus(200);
        $response->assertViewIs('admin.sections.events.index');
        $response->assertViewHas('section', $this->section);
    }

    /**
     * @test
     */
    public function admin_can_view_section_event_create_form()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.sections.events.create', $this->section));

        $response->assertStatus(200);
        $response->assertViewIs('admin.sections.events.create');
        $response->assertViewHas('section', $this->section);
    }

    /**
     * @test
     */
    public function admin_can_create_section_event()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.sections.events.store', $this->section), [
                'title' => 'Test Event',
                'description' => 'Test Event Description',
                'start_date' => '2024-12-25 10:00:00',
                'end_date' => '2024-12-25 18:00:00',
                'location' => 'Test Location',
                'event_type' => 'meeting',
                'status' => 'upcoming',
                'is_featured' => true,
                'is_active' => true
            ]);

        $response->assertRedirect(route('admin.sections.events.index', $this->section));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('section_events', [
            'section_id' => $this->section->id,
            'title' => 'Test Event',
            'event_type' => 'meeting',
            'status' => 'upcoming',
            'is_featured' => true,
            'is_active' => true
        ]);
    }

    /**
     * @test
     */
    public function admin_can_view_section_event_edit_form()
    {
        $event = SectionEvent::factory()->create(['section_id' => $this->section->id]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.sections.events.edit', [$this->section, $event]));

        $response->assertStatus(200);
        $response->assertViewIs('admin.sections.events.edit');
        $response->assertViewHas('section', $this->section);
        $response->assertViewHas('event', $event);
    }

    /**
     * @test
     */
    public function admin_can_update_section_event()
    {
        $event = SectionEvent::factory()->create(['section_id' => $this->section->id]);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.sections.events.update', [$this->section, $event]), [
                'title' => 'Updated Event',
                'description' => 'Updated Event Description',
                'start_date' => '2024-12-26 10:00:00',
                'event_type' => 'rally',
                'status' => 'ongoing',
                'is_featured' => false,
                'is_active' => true
            ]);

        $response->assertRedirect(route('admin.sections.events.index', $this->section));
        $response->assertSessionHas('success');
        
        $event->refresh();
        $this->assertEquals('Updated Event', $event->title);
        $this->assertEquals('rally', $event->event_type);
        $this->assertEquals('ongoing', $event->status);
        $this->assertFalse($event->is_featured);
    }

    /**
     * @test
     */
    public function admin_can_delete_section_event()
    {
        $event = SectionEvent::factory()->create(['section_id' => $this->section->id]);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.sections.events.destroy', [$this->section, $event]));

        $response->assertRedirect(route('admin.sections.events.index', $this->section));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseMissing('section_events', ['id' => $event->id]);
    }

    // ===== SECTION HIGHLIGHTS TESTS =====

    /**
     * @test
     */
    public function admin_can_view_section_highlights_index()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.sections.highlights.index', $this->section));

        $response->assertStatus(200);
        $response->assertViewIs('admin.sections.highlights.index');
        $response->assertViewHas('section', $this->section);
    }

    /**
     * @test
     */
    public function admin_can_view_section_highlight_create_form()
    {
        $response = $this->actingAs($this->admin)
            ->get(route('admin.sections.highlights.create', $this->section));

        $response->assertStatus(200);
        $response->assertViewIs('admin.sections.highlights.create');
        $response->assertViewHas('section', $this->section);
    }

    /**
     * @test
     */
    public function admin_can_create_section_highlight()
    {
        $response = $this->actingAs($this->admin)
            ->post(route('admin.sections.highlights.store', $this->section), [
                'title' => 'Test Highlight',
                'description' => 'Test Highlight Description',
                'icon' => 'fas fa-star',
                'button_text' => 'Learn More',
                'button_url' => 'https://example.com',
                'button_type' => 'primary',
                'highlight_type' => 'info',
                'is_active' => true,
                'valid_from' => '2024-01-01 00:00:00',
                'valid_until' => '2024-12-31 23:59:59'
            ]);

        $response->assertRedirect(route('admin.sections.highlights.index', $this->section));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('section_highlights', [
            'section_id' => $this->section->id,
            'title' => 'Test Highlight',
            'icon' => 'fas fa-star',
            'button_type' => 'primary',
            'highlight_type' => 'info',
            'is_active' => true
        ]);
    }

    /**
     * @test
     */
    public function admin_can_view_section_highlight_edit_form()
    {
        $highlight = SectionHighlight::factory()->create(['section_id' => $this->section->id]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.sections.highlights.edit', [$this->section, $highlight]));

        $response->assertStatus(200);
        $response->assertViewIs('admin.sections.highlights.edit');
        $response->assertViewHas('section', $this->section);
        $response->assertViewHas('highlight', $highlight);
    }

    /**
     * @test
     */
    public function admin_can_update_section_highlight()
    {
        $highlight = SectionHighlight::factory()->create(['section_id' => $this->section->id]);

        $response = $this->actingAs($this->admin)
            ->put(route('admin.sections.highlights.update', [$this->section, $highlight]), [
                'title' => 'Updated Highlight',
                'description' => 'Updated Highlight Description',
                'icon' => 'fas fa-heart',
                'button_type' => 'success',
                'highlight_type' => 'success',
                'is_active' => true
            ]);

        $response->assertRedirect(route('admin.sections.highlights.index', $this->section));
        $response->assertSessionHas('success');
        
        $highlight->refresh();
        $this->assertEquals('Updated Highlight', $highlight->title);
        $this->assertEquals('fas fa-heart', $highlight->icon);
        $this->assertEquals('success', $highlight->button_type);
        $this->assertEquals('success', $highlight->highlight_type);
    }

    /**
     * @test
     */
    public function admin_can_delete_section_highlight()
    {
        $highlight = SectionHighlight::factory()->create(['section_id' => $this->section->id]);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.sections.highlights.destroy', [$this->section, $highlight]));

        $response->assertRedirect(route('admin.sections.highlights.index', $this->section));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseMissing('section_highlights', ['id' => $highlight->id]);
    }

    // ===== AUTHORIZATION TESTS =====

    /**
     * @test
     */
    public function unauthorized_user_cannot_access_sections()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('admin.sections.index'));

        $response->assertStatus(403);
    }

    /**
     * @test
     */
    public function guest_cannot_access_sections()
    {
        $response = $this->get(route('admin.sections.index'));

        $response->assertRedirect(route('login'));
    }
} 