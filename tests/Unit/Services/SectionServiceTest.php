<?php

namespace Tests\Unit\Services;

use App\Models\Section;
use App\Models\User;
use App\Models\SectionImage;
use App\Models\SectionSecretary;
use App\Models\SectionEvent;
use App\Models\SectionHighlight;
use App\Repositories\Interfaces\SectionRepositoryInterface;
use App\Services\SectionService;
use App\Services\SectionImageService;
use App\Services\SectionSecretaryService;
use App\Services\SectionEventService;
use App\Services\SectionHighlightService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use Tests\TestCase;

class SectionServiceTest extends TestCase
{
    use DatabaseTransactions;

    private SectionService $service;
    private SectionRepositoryInterface $sectionRepository;
    private SectionImageService $imageService;
    private SectionSecretaryService $secretaryService;
    private SectionEventService $eventService;
    private SectionHighlightService $highlightService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->sectionRepository = Mockery::mock(SectionRepositoryInterface::class);
        $this->imageService = Mockery::mock(SectionImageService::class);
        $this->secretaryService = Mockery::mock(SectionSecretaryService::class);
        $this->eventService = Mockery::mock(SectionEventService::class);
        $this->highlightService = Mockery::mock(SectionHighlightService::class);
        
        $this->service = new SectionService(
            $this->sectionRepository,
            $this->imageService,
            $this->secretaryService,
            $this->eventService,
            $this->highlightService
        );
    }

    /**
     * @test
     */
    public function it_should_get_paginated_sections()
    {
        // Arrange
        $expectedPaginator = Mockery::mock(LengthAwarePaginator::class);
        $this->sectionRepository->shouldReceive('getPaginatedSections')
            ->with('test search', 15, 'name', 'asc')
            ->once()
            ->andReturn($expectedPaginator);

        // Act
        $result = $this->service->getPaginatedSections('test search', 15, 'name', 'asc');

        // Assert
        $this->assertSame($expectedPaginator, $result);
    }

    /**
     * @test
     */
    public function it_should_create_section()
    {
        // Arrange
        $sectionData = [
            'name' => 'Test Section',
            'description' => 'Test Description',
            'city' => 'Test City',
            'is_active' => true
        ];
        
        $expectedSection = Section::factory()->make($sectionData);
        $this->sectionRepository->shouldReceive('create')
            ->with($sectionData)
            ->once()
            ->andReturn($expectedSection);

        // Act
        $result = $this->service->createSection($sectionData);

        // Assert
        $this->assertSame($expectedSection, $result);
    }

    /**
     * @test
     */
    public function it_should_find_section_by_id()
    {
        // Arrange
        $expectedSection = Section::factory()->make();
        $this->sectionRepository->shouldReceive('findById')
            ->with(1)
            ->once()
            ->andReturn($expectedSection);

        // Act
        $result = $this->service->findSection(1);

        // Assert
        $this->assertSame($expectedSection, $result);
    }

    /**
     * @test
     */
    public function it_should_find_section_by_slug()
    {
        // Arrange
        $expectedSection = Section::factory()->make();
        $this->sectionRepository->shouldReceive('findBySlug')
            ->with('test-section')
            ->once()
            ->andReturn($expectedSection);

        // Act
        $result = $this->service->findSectionBySlug('test-section');

        // Assert
        $this->assertSame($expectedSection, $result);
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
            'description' => 'Updated Description'
        ];
        
        $this->sectionRepository->shouldReceive('update')
            ->with($section, $updateData)
            ->once()
            ->andReturn(true);

        // Act
        $result = $this->service->updateSection($section, $updateData);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function it_should_delete_section()
    {
        // Arrange
        $section = Section::factory()->create();
        $this->sectionRepository->shouldReceive('delete')
            ->with($section)
            ->once()
            ->andReturn(true);

        // Act
        $result = $this->service->deleteSection($section);

        // Assert
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function it_should_add_member_to_section()
    {
        // Arrange
        $section = Section::factory()->create();
        $userId = 1;
        $joinedAt = '2024-01-01';
        
        $this->sectionRepository->shouldReceive('addMember')
            ->with($section, $userId, $joinedAt)
            ->once();

        // Act
        $this->service->addMemberToSection($section, $userId, $joinedAt);

        // Assert - Method should not throw exception
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function it_should_remove_member_from_section()
    {
        // Arrange
        $section = Section::factory()->create();
        $userId = 1;
        
        $this->sectionRepository->shouldReceive('removeMember')
            ->with($section, $userId)
            ->once();

        // Act
        $this->service->removeMemberFromSection($section, $userId);

        // Assert - Method should not throw exception
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function it_should_get_section_members()
    {
        // Arrange
        $section = Section::factory()->create();
        $expectedPaginator = Mockery::mock(LengthAwarePaginator::class);
        
        $this->sectionRepository->shouldReceive('getSectionMembers')
            ->with($section, 25)
            ->once()
            ->andReturn($expectedPaginator);

        // Act
        $result = $this->service->getSectionMembers($section, 25);

        // Assert
        $this->assertSame($expectedPaginator, $result);
    }

    /**
     * @test
     */
    public function it_should_get_all_sections()
    {
        // Arrange
        $expectedCollection = collect([Section::factory()->make()]);
        $this->sectionRepository->shouldReceive('getAllSections')
            ->once()
            ->andReturn($expectedCollection);

        // Act
        $result = $this->service->getAllSections();

        // Assert
        $this->assertSame($expectedCollection, $result);
    }

    /**
     * @test
     */
    public function it_should_get_complete_section_data()
    {
        // Arrange
        $section = Section::factory()->create();
        $images = collect([SectionImage::factory()->make()]);
        $featuredImages = collect([SectionImage::factory()->make()]);
        $galleryImages = collect([SectionImage::factory()->make()]);
        $logo = SectionImage::factory()->make();
        $secretaries = collect([SectionSecretary::factory()->make()]);
        $primarySecretary = SectionSecretary::factory()->make();
        $upcomingEvents = collect([SectionEvent::factory()->make()]);
        $featuredEvents = collect([SectionEvent::factory()->make()]);
        $highlights = collect([SectionHighlight::factory()->make()]);
        $urgentHighlights = collect([SectionHighlight::factory()->make()]);
        $stats = ['total' => 10, 'active' => 8];

        $this->imageService->shouldReceive('getSectionImages')->with($section)->andReturn($images);
        $this->imageService->shouldReceive('getFeaturedImages')->with($section)->andReturn($featuredImages);
        $this->imageService->shouldReceive('getGalleryImages')->with($section)->andReturn($galleryImages);
        $this->imageService->shouldReceive('getSectionLogo')->with($section)->andReturn($logo);
        $this->secretaryService->shouldReceive('getSectionSecretaries')->with($section)->andReturn($secretaries);
        $this->secretaryService->shouldReceive('getPrimarySecretary')->with($section)->andReturn($primarySecretary);
        $this->eventService->shouldReceive('getUpcomingEvents')->with($section, 5)->andReturn($upcomingEvents);
        $this->eventService->shouldReceive('getFeaturedEvents')->with($section)->andReturn($featuredEvents);
        $this->highlightService->shouldReceive('getActiveHighlights')->with($section)->andReturn($highlights);
        $this->highlightService->shouldReceive('getUrgentHighlights')->with($section)->andReturn($urgentHighlights);
        
        // Mock the getSectionStats method
        $this->service = Mockery::mock(SectionService::class, [
            $this->sectionRepository,
            $this->imageService,
            $this->secretaryService,
            $this->eventService,
            $this->highlightService
        ])->makePartial();
        
        $this->service->shouldReceive('getSectionStats')->with($section)->andReturn($stats);

        // Act
        $result = $this->service->getCompleteSectionData($section);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('section', $result);
        $this->assertArrayHasKey('images', $result);
        $this->assertArrayHasKey('featured_images', $result);
        $this->assertArrayHasKey('gallery_images', $result);
        $this->assertArrayHasKey('logo', $result);
        $this->assertArrayHasKey('secretaries', $result);
        $this->assertArrayHasKey('primary_secretary', $result);
        $this->assertArrayHasKey('upcoming_events', $result);
        $this->assertArrayHasKey('featured_events', $result);
        $this->assertArrayHasKey('highlights', $result);
        $this->assertArrayHasKey('urgent_highlights', $result);
        $this->assertArrayHasKey('stats', $result);
    }

    /**
     * @test
     */
    public function it_should_get_sections_with_map_data()
    {
        // Arrange
        $sections = collect([
            Section::factory()->make(['latitude' => 41.9028, 'longitude' => 12.4964]),
            Section::factory()->make(['latitude' => null, 'longitude' => null]),
            Section::factory()->make(['latitude' => 45.4642, 'longitude' => 9.1900])
        ]);
        
        $this->sectionRepository->shouldReceive('getAllSections')
            ->once()
            ->andReturn($sections);

        // Act
        $result = $this->service->getSectionsWithMapData();

        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEquals(2, $result->count()); // Only sections with coordinates
    }

    /**
     * @test
     */
    public function it_should_get_sections_with_upcoming_events()
    {
        // Arrange
        $sections = collect([
            Section::factory()->make(),
            Section::factory()->make()
        ]);
        
        $this->sectionRepository->shouldReceive('getAllSections')
            ->once()
            ->andReturn($sections);
        
        $this->eventService->shouldReceive('getUpcomingEvents')
            ->twice()
            ->andReturn(
                collect([SectionEvent::factory()->make()]), // First section has events
                collect([]) // Second section has no events
            );

        // Act
        $result = $this->service->getSectionsWithUpcomingEvents(10);

        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEquals(1, $result->count()); // Only sections with upcoming events
    }

    /**
     * @test
     */
    public function it_should_get_sections_with_highlights()
    {
        // Arrange
        $sections = collect([
            Section::factory()->make(),
            Section::factory()->make()
        ]);
        
        $this->sectionRepository->shouldReceive('getAllSections')
            ->once()
            ->andReturn($sections);
        
        $this->highlightService->shouldReceive('getActiveHighlights')
            ->twice()
            ->andReturn(
                collect([SectionHighlight::factory()->make()]), // First section has highlights
                collect([]) // Second section has no highlights
            );

        // Act
        $result = $this->service->getSectionsWithHighlights(10);

        // Assert
        $this->assertInstanceOf(Collection::class, $result);
        $this->assertEquals(1, $result->count()); // Only sections with highlights
    }

    /**
     * @test
     */
    public function it_should_check_enhanced_features()
    {
        // Arrange
        $section = Section::factory()->create();
        
        $this->imageService->shouldReceive('getSectionImages')->with($section)->andReturn(collect([SectionImage::factory()->make()]));
        $this->secretaryService->shouldReceive('hasActiveSecretaries')->with($section)->andReturn(true);
        $this->eventService->shouldReceive('getUpcomingEvents')->with($section, 1)->andReturn(collect([SectionEvent::factory()->make()]));
        $this->highlightService->shouldReceive('hasActiveHighlights')->with($section)->andReturn(true);

        // Act
        $result = $this->service->hasEnhancedFeatures($section);

        // Assert
        $this->assertIsArray($result);
        $this->assertArrayHasKey('has_images', $result);
        $this->assertArrayHasKey('has_secretaries', $result);
        $this->assertArrayHasKey('has_events', $result);
        $this->assertArrayHasKey('has_highlights', $result);
        $this->assertArrayHasKey('has_map', $result);
        $this->assertTrue($result['has_images']);
        $this->assertTrue($result['has_secretaries']);
        $this->assertTrue($result['has_events']);
        $this->assertTrue($result['has_highlights']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
} 