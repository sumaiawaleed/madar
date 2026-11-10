<?php

namespace App\Models;

use App\Enums\ProjectStatus;
use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['client_id', 'proposal_id', 'title', 'status', 'progress', 'budget', 'starts_on', 'due_on', 'notes'])]
class Project extends Model
{
    /** @use HasFactory<ProjectFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ProjectStatus::class,
            'progress' => 'integer',
            'budget' => 'decimal:2',
            'starts_on' => 'date',
            'due_on' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Project $project): void {
            if ($project->status === ProjectStatus::Done) {
                $project->progress = 100;
            }
        });
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
     * @return BelongsTo<Proposal, $this>
     */
    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }

    public function isOverdue(): bool
    {
        return $this->status !== ProjectStatus::Done
            && $this->due_on !== null
            && $this->due_on->isBefore(today());
    }
}
