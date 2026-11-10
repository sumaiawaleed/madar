<?php

namespace App\Http\Requests;

use App\Enums\ProjectStatus;
use App\Http\Requests\Concerns\ValidatesWorkspaceRecords;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequest extends FormRequest
{
    use ValidatesWorkspaceRecords;

    /**
     * Ownership of an existing project is checked by the controller's policy call.
     */
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
            'title' => ['required', 'string', 'max:255'],
            'client_id' => ['nullable', 'integer', $this->ownedBy('clients')],
            'proposal_id' => ['nullable', 'integer', $this->ownedBy('proposals')],
            'status' => ['required', Rule::enum(ProjectStatus::class)],
            'budget' => ['nullable', 'numeric', 'min:0', 'max:100000000'],
            'progress' => ['nullable', 'integer', 'between:0,100'],
            'starts_on' => ['nullable', 'date'],
            'due_on' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
