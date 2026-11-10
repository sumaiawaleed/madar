<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use App\Models\Concerns\HasLineItems;
use App\Models\Concerns\HasSequentialNumber;
use Database\Factories\InvoiceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['client_id', 'project_id', 'status', 'issued_on', 'due_on', 'paid_on', 'has_vat', 'items'])]
class Invoice extends Model
{
    /** @use HasFactory<InvoiceFactory> */
    use HasFactory, HasLineItems, HasSequentialNumber;

    public const float VAT_RATE = 0.15;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => InvoiceStatus::class,
            'issued_on' => 'date',
            'due_on' => 'date',
            'paid_on' => 'date',
            'has_vat' => 'boolean',
            'items' => 'array',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Invoice $invoice): void {
            if ($invoice->status !== InvoiceStatus::Paid) {
                $invoice->paid_on = null;
            } elseif ($invoice->paid_on === null) {
                $invoice->paid_on = today();
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
     * @return BelongsTo<Project, $this>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @param  Builder<Invoice>  $query
     */
    public function scopePaid(Builder $query): void
    {
        $query->where('status', InvoiceStatus::Paid);
    }

    /**
     * @param  Builder<Invoice>  $query
     */
    public function scopeOutstanding(Builder $query): void
    {
        $query->where('status', InvoiceStatus::Sent);
    }

    public function reference(): string
    {
        return 'INV-'.str_pad((string) $this->number, 4, '0', STR_PAD_LEFT);
    }

    public function vatAmount(): float
    {
        return $this->has_vat ? round($this->subtotal() * self::VAT_RATE, 2) : 0.0;
    }

    public function total(): float
    {
        return round($this->subtotal() + $this->vatAmount(), 2);
    }

    public function isOverdue(): bool
    {
        return $this->status === InvoiceStatus::Sent
            && $this->due_on !== null
            && $this->due_on->isBefore(today());
    }

    /**
     * The state shown to the freelancer, which adds "overdue" on top of the stored status.
     *
     * @return array{key: string, label: string, class: string}
     */
    public function displayState(): array
    {
        return match (true) {
            $this->status === InvoiceStatus::Paid => ['key' => 'paid', 'label' => 'مدفوعة', 'class' => 'p-green'],
            $this->status === InvoiceStatus::Draft => ['key' => 'draft', 'label' => 'مسودة', 'class' => 'p-grey'],
            $this->isOverdue() => ['key' => 'late', 'label' => 'متأخرة', 'class' => 'p-red'],
            default => ['key' => 'due', 'label' => 'مستحقة', 'class' => 'p-amber'],
        };
    }
}
