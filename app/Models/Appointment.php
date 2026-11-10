<?php

namespace App\Models;

use Database\Factories\AppointmentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['client_id', 'title', 'scheduled_on', 'starts_at', 'duration_minutes', 'type', 'location'])]
class Appointment extends Model
{
    /** @use HasFactory<AppointmentFactory> */
    use HasFactory;

    public const array TYPES = ['اجتماع مرئي', 'حضوري', 'مكالمة'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'scheduled_on' => 'date',
            'duration_minutes' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Client, $this>
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * @param  Builder<Appointment>  $query
     */
    public function scopeUpcoming(Builder $query): void
    {
        $query->whereDate('scheduled_on', '>=', today())->orderBy('scheduled_on')->orderBy('starts_at');
    }

    /**
     * @param  Builder<Appointment>  $query
     */
    public function scopePast(Builder $query): void
    {
        $query->whereDate('scheduled_on', '<', today())->orderByDesc('scheduled_on')->orderByDesc('starts_at');
    }

    public function startTime(): string
    {
        return substr((string) $this->starts_at, 0, 5);
    }
}
