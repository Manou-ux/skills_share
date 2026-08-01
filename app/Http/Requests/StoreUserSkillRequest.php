<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserSkillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'skill_id' => ['required', 'exists:skills,id'],
            'type' => ['required', 'in:offre,besoin'],
            'niveau' => ['nullable', 'required_if:type,offre', 'in:debutant,intermediaire,expert'],
            'description' => ['required', 'string', 'min:10', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'description.required' => 'Ajoutez une description pour votre offre ou besoin.',
            'description.min' => 'La description doit faire au moins 10 caractères.',
        ];
    }
}
