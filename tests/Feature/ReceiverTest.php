<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Receiver;

class ReceiverTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_can_create_receiver()
    {
        $pix = fake()->randomElement([
            ["CPF", fake()->numerify('###.###.###-##')],
            ["CNPJ", fake()->numerify('##.###.###/####-##')],
            ["EMAIL", fake()->unique()->safeEmail],
            ["TELEFONE", fake()->numerify('+55##9########')],
            ["CHAVE_ALEATORIA", fake()->uuid],
        ]);

        $response = $this->postJson('/api/receiver', [
            'name' => $this->faker->name,
            'cpf_cnpj' => fake()->unique()->randomElement([fake()->numerify('###.###.###-##'), fake()->numerify('##.###.###/####-##')]),
            'banco' => $this->faker->word,
            'agencia' => $this->faker->word,
            'conta' => $this->faker->word,
            'status' => 'validado',
            'pix_key_type' => $pix[0],
            'pix_key' => $pix[1],
            'email' => $this->faker->unique()->safeEmail,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['data' => [
                'id',
                'name',
                'cpf_cnpj',
                'banco',
                'agencia',
                'conta',
                'status',
                'pix_key_type',
                'pix_key',
                'email',
                'created_at',
                'updated_at',
            ]])->assertJsonFragment(["status" => "rascunho"]);
    }

    public function test_cannot_create_receiver_with_invalid_args()
    {
        $response = $this->postJson('/api/receiver', [
            'name' => "",
            'cpf_cnpj' => "111",
            'banco' => "",
            'agencia' => "",
            'conta' => "",
            'status' => 'rascunho',
            'pix_key_type' => 'P',
            'pix_key' => '09',
            'email' => "email",
        ]);

        $response->assertStatus(422)
            ->assertExactJson([
                'errors' => [
                    "pix_key" => ["The pix key field format is invalid."],
                    "pix_key_type" => ["The selected pix key type is invalid."],
                    "name" => ["The name field is required."],
                    "cpf_cnpj" => ["The cpf cnpj field format is invalid."],
                    "email" => ["The email field format is invalid."]
                ],
                "message" => "Validation failed"
            ]);
    }

    public function test_can_list_receivers()
    {
        Receiver::factory(3)->create();

        $response = $this->getJson('/api/receiver');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonFragment(["to" => 3, "total" => 3]);
    }

    public function test_can_list_a_default_of_10_items()
    {
        Receiver::factory(15)->create();

        $response = $this->getJson('/api/receiver');

        $response->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonFragment(["to" => 10, "total" => 15]);
    }

    public function test_cannot_list_more_than_50_items_in_one_request()
    {
        Receiver::factory(100)->create();

        $response = $this->getJson('/api/receiver?per_page=100');

        $response->assertStatus(200)
            ->assertJsonCount(50, 'data')
            ->assertJsonFragment(["to" => 50, "total" => 100]);
    }

    public function test_can_list_more_than_50_items_in_separeted_requests()
    {
        Receiver::factory(100)->create();

        $response = $this->getJson('/api/receiver?per_page=100');

        $response->assertStatus(200)
            ->assertJsonCount(50, 'data')
            ->assertJsonFragment(["to" => 50, "total" => 100]);

        $response = $this->getJson('/api/receiver?per_page=100&page=2');

        $response->assertStatus(200)
            ->assertJsonCount(50, 'data')
            ->assertJsonFragment(["to" => 100, "total" => 100]);
    }

    public function test_can_list_a_page_that_has_no_items()
    {
        Receiver::factory(5)->create();

        $response = $this->getJson('/api/receiver?page=3');

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => "Receivers not found"]);
    }

    public function test_can_list_paginated()
    {
        Receiver::factory(15)->create();

        $response = $this->getJson('/api/receiver?page=2');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonFragment(["total" => 15]);
    }

    public function test_can_list_and_query_receivers_by_name()
    {
        $queryString = "queryName";
        Receiver::factory()->create(["name" => $queryString]);

        $response = $this->getJson('/api/receiver?name=unkwon');

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => "Receivers not found"]);

        $response = $this->getJson('/api/receiver?name=' . $queryString);

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(["total" => 1])
            ->assertJsonFragment(["name" => $queryString]);
    }

    public function test_can_list_and_query_receivers_by_status()
    {
        $queryString = "validado";
        Receiver::factory()->create(["status" => $queryString]);

        $response = $this->getJson('/api/receiver?status=unkwon');

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => "Receivers not found"]);

        $response = $this->getJson('/api/receiver?status=' . $queryString);

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(["total" => 1])
            ->assertJsonFragment(["status" => $queryString]);
    }

    public function test_can_list_and_query_receivers_by_pix_key_type()
    {
        $queryString = "CPF";
        Receiver::factory()->create(["pix_key_type" => $queryString]);

        $response = $this->getJson('/api/receiver?pix_key_type=unkwon');

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => "Receivers not found"]);

        $response = $this->getJson('/api/receiver?pix_key_type=' . $queryString);

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(["total" => 1])
            ->assertJsonFragment(["pix_key_type" => $queryString]);
    }

    public function test_can_list_and_query_receivers_by_pix_key()
    {
        $queryString = "111.111.111-11";
        Receiver::factory()->create(["pix_key_type" => "CPF", "pix_key" => $queryString]);

        $response = $this->getJson('/api/receiver?pix_key=unkwon');

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => "Receivers not found"]);

        $response = $this->getJson('/api/receiver?pix_key=' . $queryString);

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(["total" => 1])
            ->assertJsonFragment(["pix_key" => $queryString, "pix_key_type" => "CPF"]);
    }

    public function test_cannot_find_receiver_in_query()
    {
        $queryString = "not_found";
        Receiver::factory()->create();

        $response = $this->getJson('/api/receiver?name=' . $queryString);

        $response->assertStatus(404);
    }

    public function test_can_query_receiver_by_more_than_one_field()
    {
        $queryName = "not_found";
        $queryStatus = "rascunho";
        Receiver::factory()->create(["name" => $queryName, "status" => $queryStatus]);

        $response = $this->getJson('/api/receiver?name=unknown&status=unknown');

        $response->assertStatus(404)
            ->assertJsonFragment(['message' => "Receivers not found"]);

        $response = $this->getJson('/api/receiver?name=' . $queryName . "&status=" . $queryStatus);

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(["total" => 1])
            ->assertJsonFragment(["name" => $queryName, "status" => $queryStatus]);
    }

    public function test_can_show_receiver()
    {
        $receiver = Receiver::factory()->create();

        $response = $this->getJson('/api/receiver/' . $receiver->id);

        $response->assertStatus(200)
            ->assertJsonFragment(['id' => $receiver->id]);
    }

    public function test_cannot_show_a_receiver_that_does_exists()
    {
        $response = $this->getJson('/api/receiver/' . -1);

        $response->assertStatus(404);
    }

    public function test_can_only_update_receiver_email_if_status_is_validated()
    {
        $email = $this->faker->unique()->safeEmail;
        $receiver = Receiver::factory()->create(["status" => "validado"]);

        $response = $this->putJson('/api/receiver/' . $receiver->id, [
            "name" => "my_name",
            'email' => $email,
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['email' => $email, 'name' => $receiver["name"], "status" => "validado"]);
    }

    public function test_can_update_all_fields_if_draft()
    {
        $receiver = Receiver::factory()->create(["status" => "rascunho"]);

        $response = $this->putJson('/api/receiver/' . $receiver->id, [
            'name' => "My New Name",
            'cpf_cnpj' => "123.456.789-00",
            'banco' => "My new bank",
            'agencia' => "My new bank",
            'conta' => "12345",
            'pix_key_type' => "TELEFONE",
            'pix_key' => "+5511911111111",
            'email' => "myemail@example.com"
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                "email" => "myemail@example.com",
                "pix_key_type" => "TELEFONE",
                "pix_key" => "+5511911111111"
            ]);
    }

    public function test_cannot_update_receiver_status()
    {
        $receiver = Receiver::factory()->create(['status' => 'validado']);

        $response = $this->putJson('/api/receiver/' . $receiver->id, [
            'status' => 'rascunho',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['status']);
    }

    public function test_can_delete_receiver()
    {
        $receiver = Receiver::factory()->create();

        $response = $this->deleteJson('/api/receiver/' . $receiver->id);

        $response->assertStatus(204);

        $this->assertNull(Receiver::find($receiver->id));
    }

    public function test_cannot_delete_a_receiver_that_does_not_exists()
    {
        $response = $this->deleteJson('/api/receiver/' . -1);

        $response->assertStatus(404);
    }

    public function test_can_delete_many_receivers()
    {
        $receiver = Receiver::factory(3)->create();

        $ids = array_map(function ($element) {
            return $element["id"];
        }, $receiver->toArray());

        $response = $this->deleteJson('/api/receivers', ["ids" => $ids]);

        $response->assertStatus(204);

        $this->assertEmpty(Receiver::whereIn("id", $ids)->get());
    }
}
