<?php

namespace App\Http\Controllers;

use App\Enums\InvoiceStatus;
use App\Http\Requests\ClientRequest;
use App\Models\Client;
use App\Models\Invoice;
use App\Support\LogoUpload;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q'));

        $clients = $request->user()->clients()
            ->withCount('projects')
            ->with(['invoices' => fn ($query) => $query->where('status', InvoiceStatus::Paid)])
            ->when($search !== '', fn ($query) => $query->where(fn ($query) => $query
                ->where('name', 'like', "%{$search}%")
                ->orWhere('company', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")))
            ->orderBy('name')
            ->get()
            ->each(fn (Client $client) => $client->setAttribute('paid_total', $client->invoices->sum(fn (Invoice $invoice): float => $invoice->total())));

        return view('clients.index', [
            'clients' => $clients,
            'search' => $search,
            'totalCount' => $request->user()->clients()->count(),
        ]);
    }

    public function create(): View
    {
        return view('clients.form', ['client' => new Client]);
    }

    public function store(ClientRequest $request): RedirectResponse
    {
        $request->user()->clients()->create([
            ...$request->safe()->except(['logo', 'remove_logo']),
            'logo_path' => LogoUpload::sync($request, null),
        ]);

        return redirect()->route('clients.index')->with('status', 'أُضيف العميل');
    }

    public function edit(Client $client): View
    {
        Gate::authorize('update', $client);

        return view('clients.form', ['client' => $client]);
    }

    public function update(ClientRequest $request, Client $client): RedirectResponse
    {
        Gate::authorize('update', $client);

        $client->update([
            ...$request->safe()->except(['logo', 'remove_logo']),
            'logo_path' => LogoUpload::sync($request, $client->logo_path),
        ]);

        return redirect()->route('clients.index')->with('status', 'تم حفظ العميل');
    }

    public function destroy(Client $client): RedirectResponse
    {
        Gate::authorize('delete', $client);

        LogoUpload::delete($client->logo_path);
        $client->delete();

        return redirect()->route('clients.index')->with('status', 'تم حذف العميل');
    }
}
