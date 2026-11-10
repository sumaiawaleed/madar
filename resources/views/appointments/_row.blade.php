<div class="row">
    <div class="date-chip"><b class="num">{{ $appointment->scheduled_on->day }}</b><small>{{ $appointment->scheduled_on->translatedFormat('M') }}</small></div>
    <div class="grow">
        <div class="t">{{ $appointment->title }}</div>
        <div class="s"><span class="num">{{ $appointment->startTime() }}</span> · {{ $appointment->type }}@if ($appointment->location) · {{ $appointment->location }}@endif</div>
    </div>
</div>
