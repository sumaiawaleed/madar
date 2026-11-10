<?php

namespace App\Support;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Support\Collection;

class MonthlyEarnings
{
    /**
     * Pre-VAT income from paid invoices, one entry per month, oldest first.
     *
     * @return Collection<int, array{key: string, label: string, total: float}>
     */
    public static function for(User $user, int $months): Collection
    {
        $start = today()->startOfMonth()->subMonths($months - 1);

        $totals = $user->invoices()
            ->paid()
            ->whereDate('paid_on', '>=', $start)
            ->get(['id', 'items', 'paid_on'])
            ->groupBy(fn (Invoice $invoice): string => $invoice->paid_on->format('Y-m'))
            ->map(fn (Collection $invoices): float => round($invoices->sum(fn (Invoice $invoice): float => $invoice->subtotal()), 2));

        return collect(range(0, $months - 1))->map(function (int $offset) use ($start, $totals): array {
            $month = $start->copy()->addMonths($offset);

            return [
                'key' => $month->format('Y-m'),
                'label' => $month->translatedFormat('M'),
                'total' => (float) ($totals[$month->format('Y-m')] ?? 0),
            ];
        });
    }
}
