<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class UpdateReceiverRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'cpf_cnpj' => ['nullable', 'string', 'regex:/^(\d{3}\.\d{3}\.\d{3}-\d{2}|\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2})$/'],
            'banco' => 'nullable|string|max:255',
            'agencia' => 'nullable|string|max:255',
            'conta' => 'nullable|string|max:255',
            'status' => 'prohibited',
            'pix_key_type' => ['nullable', Rule::in(['CPF', 'CNPJ', 'EMAIL', 'TELEFONE', 'CHAVE_ALEATORIA'])],
            'pix_key' => [
                'nullable',
                'string',
                'max:140',
                'regex:/^(\d{3}\.\d{3}\.\d{3}-\d{2}|cnpj: \d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}|[a-z0-9+_.-]+@[a-z0-9.-]+|\(\d{2}\) \d{4,5}-\d{4}|[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12})$/i'
            ],
            'email' => ['nullable', 'string', 'max:250', 'regex:/^[a-z0-9+_.-]+@[a-z0-9.-]+$/i'],
        ];
    }
}
