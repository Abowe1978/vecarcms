<?php

namespace Tests\Unit\Repositories;

use App\Models\Section;
use App\Models\User;
use App\Repositories\SectionRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

class SectionRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    private SectionRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new SectionRepository(new Section(), app('db'));
    }

    /**
     * @test
     */
    public function it_should_get_paginated_sections()
    {
        // Arrange
        Section::factory()->count(15)->create();

        // Act
        $result = $this->repository->getPaginatedSections(null, 10, 'created_at', 'desc');

        // Assert
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(10, $result->perPage());
        $this->assertEquals(15, $result->total());
    }

    /**
     * @test
     */
    public function it_should_get_paginated_sections_with_search()
    {
        // Arrange
        Section::factory()->create(['name' => 'Roma Section', 'city' => 'Roma']);
        Section::factory()->create(['name' => 'Milano Section', 'city' => 'Milano']);
        Section::factory()->create(['name' => 'Napoli Section', 'city' => 'Napoli']);

        // Act
        $result = $this->repository->getPaginatedSections('Roma', 10, 'name', 'asc');

        // Assert
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(1, $result->total());
        $this->assertEquals('Roma Section', $result->first()->name);
    }

    /**
     * @test
     */
    public function it_should_get_paginated_sections_with_user_count()
    {
        // Arrange
        $section = Section::factory()->create();
        $users = User::factory()->count(3)->create();
        $section->users()->attach($users->pluck('id'));

        // Act
        $result = $this->repository->getPaginatedSections();

        // Assert
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(3, $result->first()->users_count);
    }

    /**
     * @test
     */
    public function it_should_create_section()
    {
        // Arrange
        $data = [
            'name' => 'Test Section',
            'description' => 'Test Description',
            'city' => 'Test City',
            'country' => 'Italy',
            'is_active' => true
        ];

        // Act
        $result = $this->repository->create($data);

        // Assert
        $this->assertInstanceOf(Section::class, $result);
        $this->assertEquals('Test Section', $result->name);
        $this->assertEquals('test-section', $result->slug);
        $this->assertEquals('Test Description', $result->description);
        $this->assertEquals('Test City', $result->city);
        $this->assertEquals('Italy', $result->country);
        $this->assertTrue($result->is_active);
    }

    /**
     * @test
     */
    public function it_should_find_section_by_id()
    {
        // Arrange
        $section = Section::factory()->create();

        // Act
        $result = $this->repository->findById($section->id);

        // Assert
        $this->assertInstanceOf(Section::class, $result);
        $this->assertEquals($section->id, $result->id);
    }

    /**
     * @test
     */
    public function it_should_return_null_when_finding_nonexistent_section_by_id()
    {
        // Act
        $result = $this->repository->findById(99999);

        // Assert
        $this->assertNull($result);
    }

    /**
     * @test
     */
    public function it_should_find_section_by_slug()
    {
        // Arrange
        $section = Section::factory()->create(['name' => 'Test Section']);

        // Act
        $result = $this->repository->findBySlug('test-section');

        // Assert
        $this->assertInstanceOf(Section::class, $result);
        $this->assertEquals($section->id, $result->id);
        $this->assertEquals('test-section', $result->slug);
    }

    /**
     * @test
     */
    public function it_should_return_null_when_finding_nonexistent_section_by_slug()
    {
        // Act
        $result = $this->repository->findBySlug('nonexistent-section');

        // Assert
        $this->assertNull($result);
    }

    /**
     * @test
     */
    public function it_should_update_section()
    {
        // Arrange
        $section = Section::factory()->create();
        $updateData = [
            'name' => 'Updated Section',
            'description' => 'Updated Description',
            'city' => 'Updated City'
        ];

        // Act
        $result = $this->repository->update($section, $updateData);

        // Assert
        $this->assertTrue($result);
        $section->refresh();
        $this->assertEquals('Updated Section', $section->name);
        $this->assertEquals('updated-section', $section->slug);
        $this->assertEquals('Updated Description', $section->description);
        $this->assertEquals('Updated City', $section->city);
    }

    /**
     * @test
     */
    public function it_should_delete_section()
    {
        // Arrange
        $section = Section::factory()->create();
        $users = User::factory()->count(2)->create();
        $section->users()->attach($users->pluck('id'));

        // Act
        $result = $this->repository->delete($section);

        // Assert
        $this->assertTrue($result);
        $this->assertDatabaseMissing('sections', ['id' => $section->id]);
        $this->assertDatabaseMissing('section_user', ['section_id' => $section->id]);
    }

    /**
     * @test
     */
    public function it_should_add_member_to_section()
    {
        // Arrange
        $section = Section::factory()->create();
        $user = User::factory()->create();
        $joinedAt = '2024-01-01 10:00:00';

        // Act
        $this->repository->addMember($section, $user->id, $joinedAt);

        // Assert
        $this->assertDatabaseHas('section_user', [
            'section_id' => $section->id,
            'user_id' => $user->id,
            'joined_at' => $joinedAt
        ]);
    }

    /**
     * @test
     */
    public function it_should_add_member_to_section_without_joined_at()
    {
        // Arrange
        $section = Section::factory()->create();
        $user = User::factory()->create();

        // Act
        $this->repository->addMember($section, $user->id);

        // Assert
        $this->assertDatabaseHas('section_user', [
            'section_id' => $section->id,
            'user_id' => $user->id
        ]);
    }

    /**
     * @test
     */
    public function it_should_remove_member_from_section()
    {
        // Arrange
        $section = Section::factory()->create();
        $user = User::factory()->create();
        $section->users()->attach($user->id);

        // Act
        $this->repository->removeMember($section, $user->id);

        // Assert
        $this->assertDatabaseMissing('section_user', [
            'section_id' => $section->id,
            'user_id' => $user->id
        ]);
    }

    /**
     * @test
     */
    public function it_should_get_section_members_with_pagination()
    {
        // Arrange
        $section = Section::factory()->create();
        $users = User::factory()->count(25)->create();
        $section->users()->attach($users->pluck('id'));

        // Act
        $result = $this->repository->getSectionMembers($section, 10);

        // Assert
        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertEquals(10, $result->perPage());
        $this->assertEquals(25, $result->total());
    }

    /**
     * @test
     */
    public function it_should_get_all_sections()
    {
        // Arrange
        Section::factory()->count(5)->create();

        // Act
        $result = $this->repository->getAllSections();

        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEquals(5, $result->count());
    }

    /**
     * @test
     */
    public function it_should_handle_section_creation_with_slug_generation()
    {
        // Arrange
        $data = [
            'name' => 'Special Characters & Numbers 123',
            'description' => 'Test Description',
            'is_active' => true
        ];

        // Act
        $result = $this->repository->create($data);

        // Assert
        $this->assertInstanceOf(Section::class, $result);
        $this->assertEquals('special-characters-numbers-123', $result->slug);
    }

    /**
     * @test
     */
    public function it_should_handle_section_update_with_slug_regeneration()
    {
        // Arrange
        $section = Section::factory()->create(['name' => 'Original Name']);
        $updateData = ['name' => 'New Name With Changes'];

        // Act
        $result = $this->repository->update($section, $updateData);

        // Assert
        $this->assertTrue($result);
        $section->refresh();
        $this->assertEquals('new-name-with-changes', $section->slug);
    }

    /**
     * @test
     */
    public function it_should_not_regenerate_slug_when_name_not_changed()
    {
        // Arrange
        $section = Section::factory()->create(['name' => 'Test Section']);
        $originalSlug = $section->slug;
        $updateData = ['description' => 'Updated Description'];

        // Act
        $result = $this->repository->update($section, $updateData);

        // Assert
        $this->assertTrue($result);
        $section->refresh();
        $this->assertEquals($originalSlug, $section->slug);
    }
} 