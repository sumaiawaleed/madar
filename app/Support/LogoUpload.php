<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LogoUpload
{
    /**
     * Store a newly uploaded logo or remove the current one, returning the path to keep.
     */
    public static function sync(Request $request, ?string $currentPath): ?string
    {
        if ($request->hasFile('logo')) {
            self::delete($currentPath);

            return $request->file('logo')->store('logos', 'public');
        }

        if ($request->boolean('remove_logo')) {
            self::delete($currentPath);

            return null;
        }

        return $currentPath;
    }

    public static function delete(?string $path): void
    {
        if ($path !== null) {
            Storage::disk('public')->delete($path);
        }
    }
}
