<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_dashboard_view_blade()
    {
        $response = $this->get('/admin/dashboard');

        $response->assertStatus(302);
    }

    // public function test_dashboard_page_contains_given_text()
    // {
    //     $response = $this->get('/admin/dashboard');

    //     $response->assertSee('Welcome To Admin Dashboard');
    // }
}
