<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ApiAppTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCanLogin()
    {
        $form_data = [
            'email' => 'customer@gmail.com',
            'password' => 'dummydummy'
        ];

        $this->withoutExceptionHandling();
        $this->json('POST', 'api/auth/login', $form_data)
            ->assertStatus(200);
    }


    public function testGetUserList()
    {
        // ? 1 customer , 2 staff
        $user = User::factory()->create([
            'user_type_id' => 1
        ]);
        $this->actingAs($user);

        $this->withoutExceptionHandling();
        $this->json('GET', 'api/app/userlist')
            ->assertStatus(200);
    }
}
