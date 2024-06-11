<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ReceiverResource;
use App\Http\Requests\ReceiverRequest;
use App\Http\Requests\UpdateReceiverRequest;
use App\Services\ReceiverService;

class ReceiverController extends Controller
{
    protected $receiverService;

    public function __construct(ReceiverService $receiverService)
    {
        $this->receiverService = $receiverService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'name', 'pix_key_type', 'pix_key']);
        $perPage = $request->query('per_page', 10);
        $receivers = $this->receiverService->getAll($filters, $perPage);

        if ($receivers->isEmpty()) {
            return response()->json(['message' => 'Receivers not found'], 404);
        }

        return ReceiverResource::collection($receivers);
    }

    public function store(ReceiverRequest $request)
    {
        $receiver = $this->receiverService->create($request->all());
        return new ReceiverResource($receiver);
    }

    public function show($id)
    {
        $receiver = $this->receiverService->find($id);
        if (!$receiver) {
            return response()->json(['message' => 'Receiver not found'], 404);
        }
        return new ReceiverResource($receiver);
    }

    public function update(UpdateReceiverRequest $request, $id)
    {
        $receiver = $this->receiverService->update($id, $request->all());
        if (!$receiver) {
            return response()->json(['message' => 'Receiver not found'], 404);
        }
        return new ReceiverResource($receiver);
    }

    public function destroy($id)
    {
        $deleted = $this->receiverService->delete($id);
        if (!$deleted) {
            return response()->json(['message' => 'Receiver not found'], 404);
        }
        return response()->noContent();
    }

    public function destroyMany(Request $request)
    {
        $this->receiverService->deleteMany($request->input('ids'));
        return response()->noContent();
    }
}
