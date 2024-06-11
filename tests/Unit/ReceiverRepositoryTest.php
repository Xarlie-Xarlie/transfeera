<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Receiver;
use App\Repositories\ReceiverRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReceiverRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $receiverRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->receiverRepository = new ReceiverRepository();
    }

    public function test_all_method_with_filters()
    {
        // Create test data
        Receiver::factory()->create([
            'status' => 'validado',
            'name' => 'name1',
            'pix_key_type' => 'CPF',
            'pix_key' => '123.123.123-12'
        ]);

        Receiver::factory()->create([
            'banco' => 'myNewBank',
            'status' => 'rascunho',
            'name' => 'name2',
            'pix_key_type' => 'TELEFONE',
            'pix_key' => '+44 (23) 92345-1234'
        ]);

        Receiver::factory()->create([
            'name' => 'John Doe',
            'status' => 'rascunho',
            'pix_key_type' => 'TELEFONE',
            'pix_key' => '+44 (23) 92345-1234'
        ]);

        Receiver::factory()->create([
            'pix_key_type' => 'CHAVE_ALEATORIA',
            'status' => 'rascunho',
            'name' => 'name3',
            'pix_key' => 'asdfkajsdlfj'
        ]);

        Receiver::factory()->create([
            'pix_key' => 'john@example.com',
            'status' => 'rascunho',
            'pix_key_type' => 'EMAIL'
        ]);

        $filters = ['status' => 'validado'];
        $results = $this->receiverRepository->all($filters, 10);
        $this->assertCount(1, $results);

        $filters = ['name' => 'John Doe'];
        $results = $this->receiverRepository->all($filters, 10);
        $this->assertCount(1, $results);

        $filters = ['pix_key_type' => 'CHAVE_ALEATORIA'];
        $results = $this->receiverRepository->all($filters, 10);
        $this->assertCount(1, $results);

        $filters = ['pix_key' => 'john@example.com'];
        $results = $this->receiverRepository->all($filters, 10);
        $this->assertCount(1, $results);

        $results = $this->receiverRepository->all([], 10);
        $this->assertCount(5, $results);
    }

    public function test_create_method()
    {
        $data = [
            'status' => 'rascunho',
            'name' => 'Jane Doe',
            'pix_key_type' => 'TELEFONE',
            'pix_key' => '+33 (12) 23456-7890',
            'cpf_cnpj' => '123.123.123-12',
            'banco' => 'bank',
            'agencia' => '1234',
            'conta' => '123456'
        ];

        $receiver = $this->receiverRepository->create($data);

        $this->assertInstanceOf(Receiver::class, $receiver);
        $this->assertEquals('Jane Doe', $receiver->name);
    }

    public function test_find_method()
    {
        $receiver = Receiver::factory()->create();

        $foundReceiver = $this->receiverRepository->find($receiver->id);

        $this->assertInstanceOf(Receiver::class, $foundReceiver);
        $this->assertEquals($receiver->id, $foundReceiver->id);
    }

    public function test_update_method()
    {
        $receiver = Receiver::factory()->create();

        $data = ['name' => 'Updated Name'];
        $updatedReceiver = $this->receiverRepository->update($receiver->id, $data);

        $this->assertInstanceOf(Receiver::class, $updatedReceiver);
        $this->assertEquals('Updated Name', $updatedReceiver->name);
    }

    public function test_update_method_with_invalid_id()
    {
        $data = ['name' => 'Updated Name'];
        $updatedReceiver = $this->receiverRepository->update(999, $data);

        $this->assertNull($updatedReceiver);
    }

    public function test_delete_method()
    {
        $receiver = Receiver::factory()->create();

        $result = $this->receiverRepository->delete($receiver->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('receivers', ['id' => $receiver->id]);
    }

    public function test_delete_method_with_invalid_id()
    {
        $result = $this->receiverRepository->delete(999);

        $this->assertFalse($result);
    }

    public function test_delete_many_method()
    {
        $receivers = Receiver::factory()->count(3)->create();

        $ids = $receivers->pluck('id')->toArray();

        $result = $this->receiverRepository->deleteMany($ids);

        $this->assertEquals(3, $result);
        foreach ($ids as $id) {
            $this->assertDatabaseMissing('receivers', ['id' => $id]);
        }
    }
}
