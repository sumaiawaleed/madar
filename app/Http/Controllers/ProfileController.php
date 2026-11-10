<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Support\LogoUpload;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile', ['user' => $request->user()]);
    }

    public function update(ProfileRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->update([
            ...$request->safe()->except(['logo', 'remove_logo']),
            'logo_path' => LogoUpload::sync($request, $user->logo_path),
        ]);

        return redirect()->route('profile.edit')->with('status', 'حُفظت بياناتك');
    }
}
