<?php

namespace App\Http\Requests;

use App\Enums\InvoiceStatus;
use App\Http\Requests\Concerns\ValidatesWorkspaceRecords;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InvoiceRequest extends FormRequest
{
    use ValidatesWorkspaceRecords;

    /**
     * Ownership of an existing invoice is checked by the controller's policy call.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->prepareLineItems();
        $this->merge(['has_vat' => $this->boolean('has_vat')]);
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client_id' => ['required', 'integer', $this->ownedBy('clients')],
            'project_id' => ['nullable', 'integer', $this->ownedBy('projects')],
            'status' => ['required', Rule::enum(InvoiceStatus::class)],
            'issued_on' => ['required', 'date'],
            'due_on' => ['nullable', 'date', 'after_or_equal:issued_on'],
            'has_vat' => ['boolean'],
            ...$this->lineItemRules(),
        ];
    }
}
