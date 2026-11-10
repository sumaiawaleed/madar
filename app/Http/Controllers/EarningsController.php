<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Support\MonthlyEarnings;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class EarningsController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();
        $months = MonthlyEarnings::for($user, 12);
        $paid = $user->invoices()->paid()->with('client')->get();

        $byClient = $paid->groupBy('client_id')
            ->map(fn (Collection $invoices): array => [
                'name' => $invoices->first()->client?->displayName() ?? 'بدون عميل',
                'total' => $invoices->sum(fn (Invoice $invoice): float => $invoice->subtotal()),
            ])
            ->sortByDesc('total')
            ->values();

        $yearTotal = $months->sum('total');

        return view('earnings', [
            'months' => $months,
            'yearTotal' => $yearTotal,
            'monthlyAverage' => $yearTotal / 12,
            'bestMonth' => $months->sortByDesc('total')->first(),
            'vatCollected' => $paid->sum(fn (Invoice $invoice): float => $invoice->vatAmount()),
            'byClient' => $byClient,
            'byClientMax' => max(1, (float) $byClient->max('total')),
        ]);
    }
}
