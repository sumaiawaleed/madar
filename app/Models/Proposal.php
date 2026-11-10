<?php

namespace App\Models;

use App\Enums\ProposalStatus;
use App\Models\Concerns\HasLineItems;
use App\Models\Concerns\HasSequentialNumber;
use Database\Factories\ProposalFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['client_id', 'title', 'status', 'issued_on', 'valid_until', 'items', 'notes'])]
class Proposal extends Model
{
    /** @use HasFactory<ProposalFactory> */
    use HasFactory, HasLineItems, HasSequentialNumber;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ProposalStatus::class,
            'issued_on' => 'date',
            'valid_until' => 'date',
            'items' => 'array',
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
     * @return HasOne<Project, $this>
     */
    public function project(): HasOne
    {
        return $this->hasOne(Project::class);
    }

    public function reference(): string
    {
        return 'Q-'.str_pad((string) $this->number, 3, '0', STR_PAD_LEFT);
    }

    public function canBecomeProject(): bool
    {
        return $this->status === ProposalStatus::Accepted && ! $this->project;
    }
}
