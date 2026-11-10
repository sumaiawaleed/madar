<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class PublicLinkPageController extends Controller
{
    public function __invoke(string $handle): View
    {
        $user = User::query()->where('handle', strtolower($handle))->firstOrFail();

        return view('links.public', ['user' => $user, 'links' => $user->links()->get()]);
    }
}
