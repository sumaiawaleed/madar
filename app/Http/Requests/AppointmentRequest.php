<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ValidatesWorkspaceRecords;
use App\Models\Appointment;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AppointmentRequest extends FormRequest
{
    use ValidatesWorkspaceRecords;

    /**
     * Ownership of an existing appointment is checked by the controller's policy call.
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
            'type' => ['required', Rule::in(Appointment::TYPES)],
            'scheduled_on' => ['required', 'date'],
            'starts_at' => ['required', 'date_format:H:i'],
            'duration_minutes' => ['nullable', 'integer', 'between:5,1440'],
            'location' => ['nullable', 'string', 'max:255'],
        ];
    }
}
