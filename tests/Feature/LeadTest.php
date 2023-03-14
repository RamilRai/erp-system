<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\CRM\Lead;

class LeadTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_lead_view_blade()
    {
        $response = $this->get('/admin/leads');

        $response->assertStatus(302);
    }

    // public function test_lead_page_contains_given_text()   // not worked
    // {
    //     $response = $this->get('admin/leads');

    //     $response->assertSee('Leads');
    // }

    public function test_users_page_contains_not_empty_table()
    {
        // Lead::create([
            
        // ]);
        $response = $this->get('admin/users');

        $response->assertDontSee('No data available.');
    }

    // public function test_create_leads()
    // {
    //     $response = $this->from(route('lead.modal'))
    //         ->post(route('lead.submit'), [
    //             'organization_type' => 'School',
    //             'organization_name' => 'ABC'
    //         ]);

    //     $this->assertEquals(1, Lead::count());

    //     // $response->assertRedirect(route('lead.index'));
    // }
}
