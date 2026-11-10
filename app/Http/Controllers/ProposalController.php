<?php

namespace App\Http\Controllers;

use App\Enums\ProposalStatus;
use App\Http\Requests\ProposalRequest;
use App\Models\Proposal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProposalController extends Controller
{
    public function index(Request $request): View
    {
        $request->validate(['status' => ['nullable', Rule::enum(ProposalStatus::class)]]);
        $status = $request->enum('status', ProposalStatus::class);

        return view('proposals.index', [
            'proposals' => $request->user()->proposals()
                ->with(['client', 'project'])
                ->when($status, fn ($query) => $query->where('status', $status))
                ->latest('number')
                ->get(),
            'status' => $status,
        ]);
    }

    public function create(Request $request): View|RedirectResponse
    {
        if (! $request->user()->clients()->exists()) {
            return redirect()->route('clients.create')->with('status', 'أضف عميلًا أولًا');
        }

        return view('proposals.form', [
            'proposal' => new Proposal([
                'client_id' => $request->integer('client') ?: null,
                'status' => ProposalStatus::Draft,
                'issued_on' => today(),
                'valid_until' => today()->addDays(14),
            ]),
            'clients' => $request->user()->clients()->orderBy('name')->get(),
        ]);
    }

    public function store(ProposalRequest $request): RedirectResponse
    {
        $request->user()->proposals()->create([
            ...$request->validated(),
            'items' => ProposalRequest::castLineItems($request->validated('items')),
        ]);

        return redirect()->route('proposals.index')->with('status', 'تم حفظ العرض');
    }

    public function show(Proposal $proposal): View
    {
        Gate::authorize('view', $proposal);

        return view('documents.show', ['document' => $proposal->load('client', 'user'), 'isInvoice' => false]);
    }

    public function edit(Request $request, Proposal $proposal): View
    {
        Gate::authorize('update', $proposal);

        return view('proposals.form', [
            'proposal' => $proposal,
            'clients' => $request->user()->clients()->orderBy('name')->get(),
        ]);
    }

    public function update(ProposalRequest $request, Proposal $proposal): RedirectResponse
    {
        Gate::authorize('update', $proposal);

        $proposal->update([
            ...$request->validated(),
            'items' => ProposalRequest::castLineItems($request->validated('items')),
        ]);

        return redirect()->route('proposals.index')->with('status', 'تم حفظ العرض');
    }

    public function destroy(Proposal $proposal): RedirectResponse
    {
        Gate::authorize('delete', $proposal);

        $proposal->delete();

        return redirect()->route('proposals.index')->with('status', 'تم الحذف');
    }
}
