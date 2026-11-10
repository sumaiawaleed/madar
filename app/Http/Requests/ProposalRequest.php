<?php

namespace App\Http\Requests;

use App\Enums\ProposalStatus;
use App\Http\Requests\Concerns\ValidatesWorkspaceRecords;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProposalRequest extends FormRequest
{
    use ValidatesWorkspaceRecords;

    /**
     * Ownership of an existing proposal is checked by the controller's policy call.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->prepareLineItems();
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'client_id' => ['required', 'integer', $this->ownedBy('clients')],
            'status' => ['required', Rule::enum(ProposalStatus::class)],
            'issued_on' => ['required', 'date'],
            'valid_until' => ['nullable', 'date', 'after_or_equal:issued_on'],
            'notes' => ['nullable', 'string', 'max:5000'],
            ...$this->lineItemRules(),
        ];
    }
}
