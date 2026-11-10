@props(['data', 'label' => 'الأرباح الشهرية'])
@php
    $width = 560; $height = 220; $padLeft = 56; $padBottom = 28; $padTop = 12;
    $max = max(1, (float) $data->max('total'));
    $step = 10 ** floor(log10($max));
    $nice = ceil($max / $step) * $step;
    $ticks = [0, $nice / 2, $nice];
    $barSlot = ($width - $padLeft - 16) / max(1, $data->count());
    $y = fn (float $value): float => round($height - $padBottom - ($value / $nice) * ($height - $padBottom - $padTop), 2);
    $short = fn (float $value): string => $value >= 1000 ? rtrim(rtrim(number_format($value / 1000, 1), '0'), '.').'k' : (string) round($value);
@endphp
<div class="chart">
    <svg viewBox="0 0 {{ $width }} {{ $height }}" role="img" aria-label="{{ $label }}">
        <defs><linearGradient id="g1" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#C77DFF"/><stop offset="1" stop-color="#7B3FF2"/></linearGradient></defs>
        @foreach ($ticks as $tick)
            <line x1="{{ $padLeft }}" x2="{{ $width - 8 }}" y1="{{ $y($tick) }}" y2="{{ $y($tick) }}" stroke="var(--line)" @if ($tick) stroke-dasharray="3 4" @endif />
            <text x="{{ $padLeft - 8 }}" y="{{ $y($tick) + 4 }}" text-anchor="end">{{ $short($tick) }}</text>
        @endforeach
        @foreach ($data->values() as $index => $month)
            @php($x = round($padLeft + $index * $barSlot + $barSlot * 0.22, 2))
            @php($barWidth = round($barSlot * 0.56, 2))
            <rect x="{{ $x }}" y="{{ $y($month['total']) }}" width="{{ $barWidth }}" height="{{ max(0, $height - $padBottom - $y($month['total'])) }}" rx="4" fill="{{ $loop->last ? 'url(#g1)' : '#e7dcfd' }}"/>
            @if ($month['total'] > 0)
                <text x="{{ $x + $barWidth / 2 }}" y="{{ $y($month['total']) - 6 }}" text-anchor="middle" style="fill:var(--ink);font-weight:600">{{ $short($month['total']) }}</text>
            @endif
            <text x="{{ $x + $barWidth / 2 }}" y="{{ $height - 8 }}" text-anchor="middle">{{ $month['label'] }}</text>
        @endforeach
    </svg>
</div>
