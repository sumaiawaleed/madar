<?php

namespace App\Http\Controllers;

use App\Enums\InvoiceStatus;
use App\Http\Requests\InvoiceRequest;
use App\Models\Invoice;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(Request $request): View
    {
        $request->validate(['state' => ['nullable', Rule::in(['due', 'late', 'paid', 'draft'])]]);
        $state = $request->query('state');

        $invoices = $request->user()->invoices()->with('client')->latest('number')->get();

        return view('invoices.index', [
            'invoices' => $state ? $invoices->filter(fn (Invoice $invoice): bool => $invoice->displayState()['key'] === $state) : $invoices,
            'state' => $state,
            'collected' => $invoices->where('status', InvoiceStatus::Paid)->sum(fn (Invoice $invoice): float => $invoice->total()),
            'awaiting' => $invoices->where('status', InvoiceStatus::Sent)->sum(fn (Invoice $invoice): float => $invoice->total()),
            'count' => $invoices->count(),
        ]);
    }

    public function create(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        if (! $user->clients()->exists()) {
            return redirect()->route('clients.create')->with('status', 'أضف عميلًا أولًا');
        }

        $invoice = new Invoice([
            'client_id' => $request->integer('client') ?: null,
            'status' => InvoiceStatus::Sent,
            'issued_on' => today(),
            'due_on' => today()->addDays(14),
            'has_vat' => true,
        ]);

        if ($request->filled('project')) {
            $project = $user->projects()->findOrFail($request->integer('project'));
            $invoice->fill([
                'client_id' => $project->client_id,
                'project_id' => $project->id,
                'items' => [['description' => $project->title, 'quantity' => 1, 'price' => (float) $project->budget]],
            ]);
        }

        return view('invoices.form', [
            'invoice' => $invoice,
            'clients' => $user->clients()->orderBy('name')->get(),
        ]);
    }

    public function store(InvoiceRequest $request): RedirectResponse
    {
        $request->user()->invoices()->create([
            ...$request->validated(),
            'items' => InvoiceRequest::castLineItems($request->validated('items')),
        ]);

        return redirect()->route('invoices.index')->with('status', 'تم حفظ الفاتورة');
    }

    public function show(Invoice $invoice): View
    {
        Gate::authorize('view', $invoice);

        return view('documents.show', ['document' => $invoice->load('client', 'user'), 'isInvoice' => true]);
    }

    public function edit(Request $request, Invoice $invoice): View
    {
        Gate::authorize('update', $invoice);

        return view('invoices.form', [
            'invoice' => $invoice,
            'clients' => $request->user()->clients()->orderBy('name')->get(),
        ]);
    }

    public function update(InvoiceRequest $request, Invoice $invoice): RedirectResponse
    {
        Gate::authorize('update', $invoice);

        $invoice->update([
            ...$request->validated(),
            'items' => InvoiceRequest::castLineItems($request->validated('items')),
        ]);

        return redirect()->route('invoices.index')->with('status', 'تم حفظ الفاتورة');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        Gate::authorize('delete', $invoice);

        $invoice->delete();

        return redirect()->route('invoices.index')->with('status', 'تم الحذف');
    }
}
