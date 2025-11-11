<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Models\Section;
use App\Policies\SectionPolicy;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SectionPolicyTest extends TestCase
{
    use DatabaseTransactions;

    private SectionPolicy $policy;
    private User $developer;
    private User $superAdmin;
    private User $admin;
    private User $moderator;
    private User $regularUser;
    private Section $section;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->policy = new SectionPolicy();
        
        // Create users with different roles
        $this->developer = User::factory()->create();
        $this->developer->assignRole('developer');
        
        $this->superAdmin = User::factory()->create();
        $this->superAdmin->assignRole('super-admin');
        
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
        
        $this->moderator = User::factory()->create();
        $this->moderator->assignRole('moderator');
        $this->moderator->givePermissionTo('manage_own_section');
        
        $this->regularUser = User::factory()->create();
        
        // Create test section
        $this->section = Section::factory()->create(['id' => 1]);
        $this->moderator->update(['section_id' => $this->section->id]);
    }

    /**
     * @test
     */
    public function developer_can_view_any_sections()
    {
        $this->assertTrue($this->policy->viewAny($this->developer));
    }

    /**
     * @test
     */
    public function super_admin_can_view_any_sections()
    {
        $this->assertTrue($this->policy->viewAny($this->superAdmin));
    }

    /**
     * @test
     */
    public function admin_can_view_any_sections()
    {
        $this->assertTrue($this->policy->viewAny($this->admin));
    }

    /**
     * @test
     */
    public function moderator_with_permission_can_view_any_sections()
    {
        $this->assertTrue($this->policy->viewAny($this->moderator));
    }

    /**
     * @test
     */
    public function regular_user_cannot_view_any_sections()
    {
        $this->assertFalse($this->policy->viewAny($this->regularUser));
    }

    /**
     * @test
     */
    public function moderator_without_permission_cannot_view_any_sections()
    {
        $moderatorWithoutPermission = User::factory()->create();
        $moderatorWithoutPermission->assignRole('moderator');
        
        $this->assertFalse($this->policy->viewAny($moderatorWithoutPermission));
    }

    /**
     * @test
     */
    public function developer_can_view_section()
    {
        $this->assertTrue($this->policy->view($this->developer, $this->section));
    }

    /**
     * @test
     */
    public function super_admin_can_view_section()
    {
        $this->assertTrue($this->policy->view($this->superAdmin, $this->section));
    }

    /**
     * @test
     */
    public function admin_can_view_section()
    {
        $this->assertTrue($this->policy->view($this->admin, $this->section));
    }

    /**
     * @test
     */
    public function moderator_can_view_own_section()
    {
        $this->assertTrue($this->policy->view($this->moderator, $this->section));
    }

    /**
     * @test
     */
    public function moderator_cannot_view_other_section()
    {
        $otherSection = Section::factory()->create();
        
        $this->assertFalse($this->policy->view($this->moderator, $otherSection));
    }

    /**
     * @test
     */
    public function moderator_without_permission_cannot_view_section()
    {
        $moderatorWithoutPermission = User::factory()->create();
        $moderatorWithoutPermission->assignRole('moderator');
        $moderatorWithoutPermission->update(['section_id' => $this->section->id]);
        
        $this->assertFalse($this->policy->view($moderatorWithoutPermission, $this->section));
    }

    /**
     * @test
     */
    public function regular_user_cannot_view_section()
    {
        $this->assertFalse($this->policy->view($this->regularUser, $this->section));
    }

    /**
     * @test
     */
    public function developer_can_create_sections()
    {
        $this->assertTrue($this->policy->create($this->developer));
    }

    /**
     * @test
     */
    public function super_admin_can_create_sections()
    {
        $this->assertTrue($this->policy->create($this->superAdmin));
    }

    /**
     * @test
     */
    public function admin_can_create_sections()
    {
        $this->assertTrue($this->policy->create($this->admin));
    }

    /**
     * @test
     */
    public function moderator_cannot_create_sections()
    {
        $this->assertFalse($this->policy->create($this->moderator));
    }

    /**
     * @test
     */
    public function regular_user_cannot_create_sections()
    {
        $this->assertFalse($this->policy->create($this->regularUser));
    }

    /**
     * @test
     */
    public function admin_without_permission_cannot_create_sections()
    {
        $adminWithoutPermission = User::factory()->create();
        $adminWithoutPermission->assignRole('admin');
        
        $this->assertFalse($this->policy->create($adminWithoutPermission));
    }

    /**
     * @test
     */
    public function developer_can_update_sections()
    {
        $this->assertTrue($this->policy->update($this->developer, $this->section));
    }

    /**
     * @test
     */
    public function super_admin_can_update_sections()
    {
        $this->assertTrue($this->policy->update($this->superAdmin, $this->section));
    }

    /**
     * @test
     */
    public function admin_can_update_sections()
    {
        $this->assertTrue($this->policy->update($this->admin, $this->section));
    }

    /**
     * @test
     */
    public function moderator_can_update_own_section()
    {
        $this->assertTrue($this->policy->update($this->moderator, $this->section));
    }

    /**
     * @test
     */
    public function moderator_cannot_update_other_section()
    {
        $otherSection = Section::factory()->create();
        
        $this->assertFalse($this->policy->update($this->moderator, $otherSection));
    }

    /**
     * @test
     */
    public function moderator_without_permission_cannot_update_section()
    {
        $moderatorWithoutPermission = User::factory()->create();
        $moderatorWithoutPermission->assignRole('moderator');
        $moderatorWithoutPermission->update(['section_id' => $this->section->id]);
        
        $this->assertFalse($this->policy->update($moderatorWithoutPermission, $this->section));
    }

    /**
     * @test
     */
    public function regular_user_cannot_update_sections()
    {
        $this->assertFalse($this->policy->update($this->regularUser, $this->section));
    }

    /**
     * @test
     */
    public function developer_can_delete_sections()
    {
        $this->assertTrue($this->policy->delete($this->developer, $this->section));
    }

    /**
     * @test
     */
    public function super_admin_can_delete_sections()
    {
        $this->assertTrue($this->policy->delete($this->superAdmin, $this->section));
    }

    /**
     * @test
     */
    public function admin_can_delete_sections()
    {
        $this->assertTrue($this->policy->delete($this->admin, $this->section));
    }

    /**
     * @test
     */
    public function moderator_cannot_delete_sections()
    {
        $this->assertFalse($this->policy->delete($this->moderator, $this->section));
    }

    /**
     * @test
     */
    public function regular_user_cannot_delete_sections()
    {
        $this->assertFalse($this->policy->delete($this->regularUser, $this->section));
    }

    /**
     * @test
     */
    public function developer_can_restore_sections()
    {
        $this->assertTrue($this->policy->restore($this->developer, $this->section));
    }

    /**
     * @test
     */
    public function super_admin_can_restore_sections()
    {
        $this->assertTrue($this->policy->restore($this->superAdmin, $this->section));
    }

    /**
     * @test
     */
    public function admin_can_restore_sections()
    {
        $this->assertTrue($this->policy->restore($this->admin, $this->section));
    }

    /**
     * @test
     */
    public function moderator_cannot_restore_sections()
    {
        $this->assertFalse($this->policy->restore($this->moderator, $this->section));
    }

    /**
     * @test
     */
    public function regular_user_cannot_restore_sections()
    {
        $this->assertFalse($this->policy->restore($this->regularUser, $this->section));
    }

    /**
     * @test
     */
    public function developer_can_force_delete_sections()
    {
        $this->assertTrue($this->policy->forceDelete($this->developer, $this->section));
    }

    /**
     * @test
     */
    public function super_admin_can_force_delete_sections()
    {
        $this->assertTrue($this->policy->forceDelete($this->superAdmin, $this->section));
    }

    /**
     * @test
     */
    public function admin_cannot_force_delete_sections()
    {
        $this->assertFalse($this->policy->forceDelete($this->admin, $this->section));
    }

    /**
     * @test
     */
    public function moderator_cannot_force_delete_sections()
    {
        $this->assertFalse($this->policy->forceDelete($this->moderator, $this->section));
    }

    /**
     * @test
     */
    public function regular_user_cannot_force_delete_sections()
    {
        $this->assertFalse($this->policy->forceDelete($this->regularUser, $this->section));
    }
} 