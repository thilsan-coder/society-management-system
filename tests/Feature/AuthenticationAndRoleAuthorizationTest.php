<?php

namespace Tests\Feature;

use App\Models\Member;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationAndRoleAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'president@test.com',
            'role' => 'president',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'president@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    public function test_normal_member_cannot_access_president_audit_logs(): void
    {
        $memberUser = User::factory()->create(['role' => 'member']);

        $response = $this->actingAs($memberUser)->get('/audit-logs');
        $response->assertStatus(403);
    }

    public function test_normal_member_cannot_view_another_members_private_profile(): void
    {
        $user1 = User::factory()->create(['role' => 'member']);
        $member1 = Member::create(['user_id' => $user1->id, 'member_number' => 'MEM-101']);

        $user2 = User::factory()->create(['role' => 'member']);
        $member2 = Member::create(['user_id' => $user2->id, 'member_number' => 'MEM-102']);

        // User 1 attempts to view Member 2's account
        $response = $this->actingAs($user1)->get("/members/{$member2->id}");
        $response->assertStatus(403);
    }
}
