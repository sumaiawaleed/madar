<x-layouts.app title="المواعيد">
    <x-page-header title="المواعيد" subtitle="اجتماعاتك ومكالماتك مع العملاء">
        <a class="btn pri" href="{{ route('appointments.create') }}">+ موعد</a>
    </x-page-header>

    @if ($upcomingByDay->isNotEmpty())
        <div style="display:flex;flex-direction:column;gap:14px">
            @foreach ($upcomingByDay as $day => $appointments)
                @php($date = \Illuminate\Support\Carbon::parse($day))
                <div class="panel">
                    <h2>{{ $date->isToday() ? 'اليوم' : ($date->isTomorrow() ? 'غدًا' : $date->translatedFormat('l j F')) }}</h2>
                    <div class="list">
                        @foreach ($appointments as $appointment)
                            <div class="row">
                                <div class="date-chip"><b class="num" style="font-size:15px">{{ $appointment->startTime() }}</b><small class="num">{{ $appointment->duration_minutes }} د</small></div>
                                <div class="grow">
                                    <div class="t">{{ $appointment->title }}</div>
                                    <div class="s">{{ $appointment->client?->displayName() ?? 'بدون عميل' }} · {{ $appointment->type }}@if ($appointment->location) · {{ $appointment->location }}@endif</div>
                                </div>
                                <a class="btn sm" href="{{ route('appointments.edit', $appointment) }}">تعديل</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="panel">
            <x-empty title="لا مواعيد قادمة" hint="جدول اجتماعك القادم مع عميل">
                <a class="btn pri" href="{{ route('appointments.create') }}">+ موعد</a>
            </x-empty>
        </div>
    @endif

    @if ($past->isNotEmpty())
        <div class="panel" style="margin-top:14px">
            <h2>مواعيد سابقة</h2>
            <div class="list">
                @foreach ($past as $appointment)
                    @include('appointments._row', ['appointment' => $appointment])
                @endforeach
            </div>
        </div>
    @endif
</x-layouts.app>
