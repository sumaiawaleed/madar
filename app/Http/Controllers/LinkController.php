<?php

namespace App\Http\Controllers;

use App\Http\Requests\LinkRequest;
use App\Models\Link;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class LinkController extends Controller
{
    public function index(Request $request): View
    {
        return view('links.index', [
            'user' => $request->user(),
            'links' => $request->user()->links()->get(),
        ]);
    }

    public function create(): View
    {
        return view('links.form', ['link' => new Link]);
    }

    public function store(LinkRequest $request): RedirectResponse
    {
        $request->user()->links()->create([
            ...$request->validated(),
            'position' => (int) $request->user()->links()->max('position') + 1,
        ]);

        return redirect()->route('links.index')->with('status', 'أُضيف الرابط');
    }

    public function edit(Link $link): View
    {
        Gate::authorize('update', $link);

        return view('links.form', ['link' => $link]);
    }

    public function update(LinkRequest $request, Link $link): RedirectResponse
    {
        Gate::authorize('update', $link);

        $link->update($request->validated());

        return redirect()->route('links.index')->with('status', 'تم حفظ الرابط');
    }

    public function destroy(Link $link): RedirectResponse
    {
        Gate::authorize('delete', $link);

        $link->delete();

        return redirect()->route('links.index')->with('status', 'تم الحذف');
    }
}
