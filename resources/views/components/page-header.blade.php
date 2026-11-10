@props(['title', 'subtitle' => null])
<div class="top">
    <div>
        <h1>{{ $title }}</h1>
        @if ($subtitle)<p>{{ $subtitle }}</p>@endif
    </div>
    <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">{{ $slot }}</div>
</div>
