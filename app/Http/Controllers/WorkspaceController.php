<?php

namespace App\Http\Controllers;

use App\Support\LogoUpload;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkspaceController extends Controller
{
    /**
     * Delete every client and record in the workspace while keeping the account and profile.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate(['confirm' => ['required', 'accepted']]);

        $user = $request->user();
        $clientLogos = $user->clients()->whereNotNull('logo_path')->pluck('logo_path');

        DB::transaction(function () use ($user): void {
            $user->invoices()->delete();
            $user->projects()->delete();
            $user->proposals()->delete();
            $user->appointments()->delete();
            $user->links()->delete();
            $user->clients()->delete();
        });

        $clientLogos->each(fn (string $path) => LogoUpload::delete($path));

        return redirect()->route('dashboard')->with('status', 'حُذفت كل السجلات');
    }
}
