<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class LinkPositionController extends Controller
{
    /**
     * Move a link one place up on the public page.
     */
    public function update(Link $link): RedirectResponse
    {
        Gate::authorize('update', $link);

        $links = $link->user->links()->get()->values();
        $index = $links->search(fn (Link $candidate): bool => $candidate->is($link));

        if ($index > 0) {
            $reordered = $links->all();
            [$reordered[$index - 1], $reordered[$index]] = [$reordered[$index], $reordered[$index - 1]];

            DB::transaction(function () use ($reordered): void {
                foreach ($reordered as $position => $item) {
                    $item->update(['position' => $position + 1]);
                }
            });
        }

        return redirect()->route('links.index');
    }
}
