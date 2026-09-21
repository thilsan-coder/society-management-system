<?php

namespace Tests\Feature;

use App\Models\PosterAndMedia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApprovalWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_draft_posters_do_not_appear_on_public_website(): void
    {
        $mediaUser = User::factory()->create(['role' => 'media']);

        $poster = PosterAndMedia::create([
            'title' => 'Unapproved Secret Poster',
            'media_type' => 'poster',
            'status' => 'draft',
            'submitted_by' => $mediaUser->id,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertDontSee('Unapproved Secret Poster');
    }

    public function test_president_approved_and_published_posters_appear_on_public_website(): void
    {
        $mediaUser = User::factory()->create(['role' => 'media']);
        $president = User::factory()->create(['role' => 'president']);

        $poster = PosterAndMedia::create([
            'title' => 'Official Public Gala Poster',
            'media_type' => 'poster',
            'status' => 'published',
            'submitted_by' => $mediaUser->id,
            'reviewed_by' => $president->id,
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Official Public Gala Poster');
    }
}
