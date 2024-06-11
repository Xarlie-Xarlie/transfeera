<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Receiver;
use App\Services\ReceiverService;
use App\Repositories\ReceiverRepositoryInterface;
use Mockery;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReceiverServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $receiverService;
    protected $receiverRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->receiverRepository = Mockery::mock(ReceiverRepositoryInterface::class);
        $this->receiverService = new ReceiverService($this->receiverRepository);
    }

    public function test_get_all_method()
    {
        $filters = ['status' => 'active'];
        $perPage = 10;

        $this->receiverRepository
            ->shouldReceive('all')
            ->with($filters, $perPage)
            ->andReturn(collect([]));

        $results = $this->receiverService->getAll($filters, $perPage);
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $results);
    }

    public function test_create_method()
    {
        $data = [
            'name' => 'Jane Doe',
            'pix_key_type' => 'phone',
            'pix_key' => '1234567890',
        ];

        $expectedData = $data;
        $expectedData['status'] = 'rascunho';

        $this->receiverRepository
            ->shouldReceive('create')
            ->with($expectedData)
            ->andReturn(new Receiver($expectedData));

        $receiver = $this->receiverService->create($data);

        $this->assertInstanceOf(Receiver::class, $receiver);
        $this->assertEquals('rascunho', $receiver->status);
    }

    public function test_find_method()
    {
        $receiver = Receiver::factory()->make(['id' => 1]);

        $this->receiverRepository
            ->shouldReceive('find')
            ->with(1)
            ->andReturn($receiver);

        $foundReceiver = $this->receiverService->find(1);

        $this->assertInstanceOf(Receiver::class, $foundReceiver);
        $this->assertEquals(1, $foundReceiver->id);
    }

    public function test_update_method()
    {
        $receiver = Receiver::factory()->make(['id' => 1, 'status' => 'draft']);

        $this->receiverRepository
            ->shouldReceive('find')
            ->with(1)
            ->andReturn($receiver);

        $updatedData = ['name' => 'Updated Name'];

        $this->receiverRepository
            ->shouldReceive('update')
            ->with(1, $updatedData)
            ->andReturn(new Receiver(array_merge($receiver->toArray(), $updatedData)));

        $updatedReceiver = $this->receiverService->update(1, $updatedData);

        $this->assertInstanceOf(Receiver::class, $updatedReceiver);
        $this->assertEquals('Updated Name', $updatedReceiver->name);
    }

    public function test_update_method_with_validated_status()
    {
        $receiver = Receiver::factory()->make(['id' => 1, 'status' => 'validado', 'email' => 'old@example.com']);

        $this->receiverRepository
            ->shouldReceive('find')
            ->with(1)
            ->andReturn($receiver);

        $updatedData = ['name' => 'Updated Name', 'email' => 'new@example.com'];

        $expectedData = ['email' => 'new@example.com'];

        $this->receiverRepository
            ->shouldReceive('update')
            ->with(1, $expectedData)
            ->andReturn(new Receiver(array_merge($receiver->toArray(), $expectedData)));

        $updatedReceiver = $this->receiverService->update(1, $updatedData);

        $this->assertInstanceOf(Receiver::class, $updatedReceiver);
        $this->assertEquals('new@example.com', $updatedReceiver->email);
        $this->assertArrayNotHasKey('name', $updatedReceiver->getChanges());
    }

    public function test_delete_method()
    {
        $this->receiverRepository
            ->shouldReceive('delete')
            ->with(1)
            ->andReturn(true);

        $result = $this->receiverService->delete(1);

        $this->assertTrue($result);
    }

    public function test_delete_many_method()
    {
        $ids = [1, 2, 3];

        $this->receiverRepository
            ->shouldReceive('deleteMany')
            ->with($ids)
            ->andReturn(true);

        $result = $this->receiverService->deleteMany($ids);

        $this->assertTrue($result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
