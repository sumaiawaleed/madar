@props(['title' => null])
<x-layouts.base :title="$title">
    <div class="auth">
        <div class="auth-card">
            <x-brand :tagline="null" />
            {{ $slot }}
        </div>
    </div>
</x-layouts.base>
