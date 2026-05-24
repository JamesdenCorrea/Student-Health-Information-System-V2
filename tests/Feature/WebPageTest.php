<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_pages_render_successfully(): void
    {
        $this->get('/')->assertOk();
        $this->get('/login')->assertOk();
        $this->get('/register')->assertOk();
    }

    public function test_dashboard_and_profiles_require_authentication(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/profiles')->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_dashboard_and_profiles(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/dashboard')->assertOk();
        $this->actingAs($user)->get('/profiles')->assertOk();
    }
}
