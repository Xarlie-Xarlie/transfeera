<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReceiverResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'cpf_cnpj' => $this->cpf_cnpj,
            'banco' => $this->banco,
            'agencia' => $this->agencia,
            'conta' => $this->conta,
            'status' => $this->status,
            'pix_key_type' => $this->pix_key_type,
            'pix_key' => $this->pix_key,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
