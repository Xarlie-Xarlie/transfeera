<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receiver;
use App\Http\Resources\ReceiverResource;
use App\Http\Requests\ReceiverRequest;
use App\Http\Requests\UpdateReceiverRequest;

class ReceiverController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $name = Receiver::query();
        $configurations = $name->paginate($perPage);

        return ReceiverResource::collection($configurations);
    }

    public function store(ReceiverRequest $request)
    {
        $params = $request->all();
        $params["status"] = "rascunho";
        $configuration = Receiver::create($params);
        return new ReceiverResource($configuration);
    }

    public function show(Receiver $receiver)
    {
        return new ReceiverResource($receiver);
    }

    public function update(UpdateReceiverRequest $request, int $id)
    {
        $receiver = Receiver::find($id);
        if (!isset($receiver) || is_null($receiver)) return response()->json(["message" => "Recebedor não encontrado"], 404);

        $params = $request->all();

        if ($receiver->status == "validado") {
            $updateParams = isset($params["email"]) ? ["email" => $params["email"]] : [];
        } else {
            $updateParams = $params;
        }
        $receiver->update($updateParams);
        return new ReceiverResource($receiver);
    }

    public function destroy(Receiver $receiver)
    {
        $receiver->delete();
        return response()->noContent();
    }

    public function destroyMany(Request $request)
    {
        $params = $request->all();
        Receiver::whereIn("id", $params["ids"])->delete();

        return response()->noContent();
    }
}
