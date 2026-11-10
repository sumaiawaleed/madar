<x-layouts.base :title="$user->name">
    <div class="public-page">
        @include('links._phone', ['user' => $user, 'links' => $links])
    </div>
</x-layouts.base>
