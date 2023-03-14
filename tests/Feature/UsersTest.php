<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class UsersTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_users_view_blade()
    {
        $response = $this->get('admin/users');

        // $response->assertSee('Add');

        $response->assertStatus(302);
    }

    public function test_users_page_contains_given_text()   // not worked
    {
        $response = $this->get('admin/users');

        $response->assertSee('No data available.');

        // $response->assertViewHas('parent_name', function(){});  // best to use for tables instead of assertSee
    }

    public function test_users_page_contains_not_empty_table()
    {
        $response = $this->get('admin/users');

        $response->assertDontSee('No data available.');
    }
}
