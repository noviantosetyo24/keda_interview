<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiStaffTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetCustomers()
    {
        // ? 1 customer , 2 staff
        $user = User::factory()->create([
            'user_type_id' => 2
        ]);
        $this->actingAs($user);

        $this->withoutExceptionHandling();
        $this->json('GET', 'api/app/staff/customers')
            ->assertStatus(200);
    }

    public function testGetCustomersDeleted()
    {
        // ? 1 customer , 2 staff
        $user = User::factory()->create([
            'user_type_id' => 2
        ]);
        $this->actingAs($user);

        $this->withoutExceptionHandling();
        $this->json('GET', 'api/app/staff/customers/deleted')
            ->assertStatus(200);
    }

    public function testGetMessageHistory()
    {
        // ? 1 customer , 2 staff
        $user = User::factory()->create([
            'user_type_id' => 2
        ]);
        $this->actingAs($user);

        $this->withoutExceptionHandling();
        $this->json('GET', 'api/app/staff/message/history')
            ->assertStatus(200);
    }

    public function testMessageStaffSend()
    {
        // ? 1 customer , 2 staff
        $user = User::factory()->create([
            'user_type_id' => 2
        ]);
        $this->actingAs($user);

        $staff = User::factory()->create([
            'user_type_id' => 2
        ]);

        $form_data = [
            'recipient_mail' => $staff->email,
            'title' => 'title dummy',
            'description' => 'description dummy'
        ];

        $this->withoutExceptionHandling();
        $this->json('POST', 'api/app/staff/message/staff/send', $form_data)
            ->assertStatus(200);
    }

    public function testMessageCustomerSend()
    {
        // ? 1 customer , 2 staff
        $user = User::factory()->create([
            'user_type_id' => 2
        ]);
        $this->actingAs($user);

        $customer = User::factory()->create([
            'user_type_id' => 1
        ]);

        $form_data = [
            'recipient_mail' => $customer->email,
            'title' => 'title dummy',
            'description' => 'description dummy'
        ];

        $this->withoutExceptionHandling();
        $this->json('POST', 'api/app/staff/message/customer/send', $form_data)
            ->assertStatus(200);
    }

    public function testCustomerDelete()
    {
        // ? 1 customer , 2 staff
        $user = User::factory()->create([
            'user_type_id' => 2
        ]);
        $this->actingAs($user);

        $customer = User::factory()->create([
            'user_type_id' => 1
        ]);

        $form_data = [
            'customer_id' => $customer->id,
        ];

        $this->withoutExceptionHandling();
        $this->json('POST', 'api/app/staff/customer/delete', $form_data)
            ->assertStatus(200);
    }
}
