<?php

namespace App\Http\Controllers;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class InvoicePaymentController extends Controller
{
    /**
     * Mark an invoice as paid today.
     */
    public function store(Invoice $invoice): RedirectResponse
    {
        Gate::authorize('update', $invoice);

        $invoice->update(['status' => InvoiceStatus::Paid, 'paid_on' => today()]);

        return back()->with('status', $invoice->reference().' مدفوعة');
    }
}
