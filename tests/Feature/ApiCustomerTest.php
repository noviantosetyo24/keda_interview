<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiCustomerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetMessageHistory()
    {
        // ? 1 customer , 2 staff
        $user = User::factory()->create([
            'user_type_id' => 1
        ]);
        $this->actingAs($user);

        $this->withoutExceptionHandling();
        $this->json('GET', 'api/app/customer/message/history')
            ->assertStatus(200);
    }

    public function testMessageSend()
    {
        // ? 1 customer , 2 staff
        $user = User::factory()->create([
            'user_type_id' => 1
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
        $this->json('POST', 'api/app/customer/message/send', $form_data)
            ->assertStatus(200);
    }

    public function testFeedbackSend()
    {
        // ? 1 customer , 2 staff
        $user = User::factory()->create([
            'user_type_id' => 1
        ]);
        $this->actingAs($user);

        $form_data = [
            'title' => 'title dummy',
            'description' => 'description dummy'
        ];

        $this->withoutExceptionHandling();
        $this->json('POST', 'api/app/customer/feedback/send', $form_data)
            ->assertStatus(200);
    }
}
