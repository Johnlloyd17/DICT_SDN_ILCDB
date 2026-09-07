<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CollapsibleChartsRenderTest extends TestCase
{
    use RefreshDatabase;

    protected function user(): User
    {
        return User::factory()->create(['email_verified_at' => now()]);
    }

    public function test_wired_dashboard_pages_render(): void
    {
        $pages = [
            '/spark/trainings',
            '/spark/trainees',
            '/click/devices',
            '/dtc/visitors',
        ];

        foreach ($pages as $page) {
            $this->actingAs($this->user())
                ->get($page)
                ->assertStatus(200);
        }
    }

    public function test_dashboard_does_not_double_include_chart_card(): void
    {
        $html = $this->actingAs($this->user())
            ->get('/dtc/visitors')
            ->getContent();

        $this->assertSame(1, substr_count($html, 'window.chartCard'));
    }
}