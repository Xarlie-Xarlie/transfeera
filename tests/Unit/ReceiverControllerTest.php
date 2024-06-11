<?php

namespace Tests\Unit;

use Tests\TestCase;
use Mockery;
use App\Models\Receiver;
use App\Services\ReceiverService;
use Illuminate\Http\Request;
use App\Http\Controllers\ReceiverController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Resources\ReceiverResource;
use App\Http\Requests\ReceiverRequest;
use App\Http\Requests\UpdateReceiverRequest;

class ReceiverControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $receiverService;
    protected $receiverController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->receiverService = Mockery::mock(ReceiverService::class);
        $this->receiverController = new ReceiverController($this->receiverService);
    }

    public function test_index_method()
    {
        $request = Request::create('/receivers', 'GET', ['status' => 'validado', 'per_page' => 10]);

        $this->receiverService
            ->shouldReceive('getAll')
            ->with(['status' => 'validado'], 10)
            ->andReturn(Receiver::factory()->count(3)->make());

        $response = $this->receiverController->index($request);

        $this->assertIsObject($response, Receiver::class);
        $this->assertCount(3, $response->collection);
    }

    public function test_index_method_not_found()
    {
        $request = Request::create('/receivers', 'GET', ['status' => 'validado', 'per_page' => 10]);

        $this->receiverService
            ->shouldReceive('getAll')
            ->with(['status' => 'validado'], 10)
            ->andReturn(collect([]));

        $response = $this->receiverController->index($request);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals(['message' => 'Receivers not found'], $response->getData(true));
    }

    public function test_store_method()
    {
        $data = [
            'name' => 'Jane Doe',
            'cpf_cnpj' => '123.456.789-00',
            'banco' => 'Sample Bank',
            'agencia' => '1234',
            'conta' => '56789-0',
            'pix_key_type' => 'CPF',
            'pix_key' => '123.456.789-00'
        ];

        $receiver = Receiver::factory()->make($data);

        $this->receiverService
            ->shouldReceive('create')
            ->with($data)
            ->andReturn($receiver);

        $request = ReceiverRequest::create('/receivers', 'POST', $data);
        $response = $this->receiverController->store($request);

        $this->assertInstanceOf(ReceiverResource::class, $response);
        $this->assertEquals($data['name'], $response->name);
    }

    public function test_show_method()
    {
        $receiver = Receiver::factory()->make(['id' => 1]);

        $this->receiverService
            ->shouldReceive('find')
            ->with(1)
            ->andReturn($receiver);

        $response = $this->receiverController->show(1);

        $this->assertInstanceOf(ReceiverResource::class, $response);
        $this->assertEquals(1, $response->id);
    }

    public function test_show_method_not_found()
    {
        $this->receiverService
            ->shouldReceive('find')
            ->with(1)
            ->andReturn(null);

        $response = $this->receiverController->show(1);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals(['message' => 'Receiver not found'], $response->getData(true));
    }

    public function test_update_method()
    {
        $data = ['name' => 'Updated Name'];
        $receiver = Receiver::factory()->make(['id' => 1, 'name' => 'Old Name']);

        $this->receiverService
            ->shouldReceive('update')
            ->with(1, $data)
            ->andReturn($receiver);

        $request = UpdateReceiverRequest::create('/receivers/1', 'PUT', $data);
        $response = $this->receiverController->update($request, 1);

        $this->assertInstanceOf(ReceiverResource::class, $response);
        $this->assertEquals('Old Name', $response->name);
    }

    public function test_update_method_not_found()
    {
        $data = ['name' => 'Updated Name'];

        $this->receiverService
            ->shouldReceive('update')
            ->with(1, $data)
            ->andReturn(null);

        $request = UpdateReceiverRequest::create('/receivers/1', 'PUT', $data);
        $response = $this->receiverController->update($request, 1);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals(['message' => 'Receiver not found'], $response->getData(true));
    }

    public function test_destroy_method()
    {
        $this->receiverService
            ->shouldReceive('delete')
            ->with(1)
            ->andReturn(true);

        $response = $this->receiverController->destroy(1);

        $this->assertEquals(204, $response->getStatusCode());
    }

    public function test_destroy_method_not_found()
    {
        $this->receiverService
            ->shouldReceive('delete')
            ->with(1)
            ->andReturn(false);

        $response = $this->receiverController->destroy(1);

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals(['message' => 'Receiver not found'], $response->getData(true));
    }

    public function test_destroy_many_method()
    {
        $ids = [1, 2, 3];

        $this->receiverService
            ->shouldReceive('deleteMany')
            ->with($ids)
            ->andReturn(true);

        $request = new Request(['ids' => $ids]);
        $response = $this->receiverController->destroyMany($request);

        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
