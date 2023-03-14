<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LoginPageTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_login_view_blade()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_login_page_contains_given_text()
    {
        $response = $this->get('/');

        $response->assertSee('Code?');  // use of assertSee is to check text within the page
    }

    public function test_check_login_details()
    {
        // $user = User::factory()->create();

        $response = $this->post('/admin/check-login', [
            'email_userName' => 'admin',
            'password' => 'password'
        ]);
        dd($response);

        $response->assertValid();
    }

    // public function test_dashboard_view_blade()
    // {
    //     $response = $this->get('/admin/dashboard');

    //     $response->assertStatus(302);
    // }
}
