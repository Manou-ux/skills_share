<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreExchangeRequestRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'receiver_id' => [
                'required',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    if ((int) $value === (int) $this->user()->id) {
                        $fail('Vous ne pouvez pas vous envoyer une demande.');
                    }
                },
            ],
            'skill_id' => ['required', 'exists:skills,id'],
            'message' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
