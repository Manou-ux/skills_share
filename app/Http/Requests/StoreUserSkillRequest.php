<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserSkillRequest extends FormRequest
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
            'skill_id' => ['required', 'exists:skills,id'],
            'type' => ['required', 'in:offre,besoin'],
            'niveau' => ['nullable', 'required_if:type,offre', 'in:debutant,intermediaire,expert'],
    
        ];
    }
}
