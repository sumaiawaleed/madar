<x-layouts.app title="الرئيسية">
    <x-page-header :title="(now()->hour < 12 ? 'صباح الخير' : 'مساء الخير').'، '.auth()->user()->firstName()" :subtitle="now()->translatedFormat('l j F')">
        <a class="btn pri" href="{{ route('invoices.create') }}">+ فاتورة</a>
        <a class="btn" href="{{ route('proposals.create') }}">+ عرض سعر</a>
    </x-page-header>

    <div class="kpis">
        <div class="kpi">
            <span>أرباح هذا الشهر</span>
            <strong><x-money :amount="$thisMonth" /></strong>
            <small>@if ($lastMonth) الشهر الماضي <x-money :amount="$lastMonth" /> @else من الفواتير المدفوعة قبل الضريبة @endif</small>
        </div>
        <div class="kpi">
            <span>مبالغ مستحقة</span>
            <strong><x-money :amount="$outstandingTotal" /></strong>
            <small><span class="num">{{ $outstandingCount }}</span> فاتورة @if ($overdueCount) · <span style="color:var(--red)"><span class="num">{{ $overdueCount }}</span> متأخرة</span> @endif</small>
        </div>
        <div class="kpi">
            <span>مشاريع نشطة</span>
            <strong class="num">{{ $activeProjects->count() }}</strong>
            <small><span class="num">{{ $activeProjects->where('status', \App\Enums\ProjectStatus::Review)->count() }}</span> بانتظار مراجعة العميل</small>
        </div>
        <div class="kpi">
            <span>عروض بانتظار الرد</span>
            <strong class="num">{{ $pendingProposalsCount }}</strong>
            <small>بقيمة <x-money :amount="$pendingProposalsValue" /></small>
        </div>
    </div>

    <div class="grid2">
        <div class="panel">
            <h2>الأرباح آخر ٦ أشهر <a href="{{ route('earnings') }}">التفاصيل</a></h2>
            <x-bar-chart :data="$months" />
        </div>

        <div class="panel">
            <h2>المواعيد القادمة <a href="{{ route('appointments.index') }}">الكل</a></h2>
            @forelse ($upcomingAppointments as $appointment)
                @if ($loop->first)<div class="list">@endif
                @include('appointments._row', ['appointment' => $appointment])
                @if ($loop->last)</div>@endif
            @empty
                <x-empty title="لا مواعيد قادمة" hint="أضف موعدًا مع عميل من صفحة المواعيد" />
            @endforelse
        </div>

        <div class="panel">
            <h2>المشاريع الجارية <a href="{{ route('projects.index') }}">اللوحة</a></h2>
            @forelse ($activeProjects->take(5) as $project)
                @if ($loop->first)<div class="list">@endif
                <div class="row">
                    <div class="grow">
                        <div class="t">{{ $project->title }}</div>
                        <div class="s">{{ $project->client?->displayName() ?? 'بدون عميل' }} · التسليم {{ $project->due_on?->translatedFormat('j F') ?? '—' }}</div>
                        <div class="bar" style="margin-top:6px"><i style="width:{{ $project->progress }}%"></i></div>
                    </div>
                    <x-pill :label="$project->status->label()" :tone="$project->status->pillClass()" />
                </div>
                @if ($loop->last)</div>@endif
            @empty
                <x-empty title="لا مشاريع جارية" hint="حوّل عرضًا مقبولًا إلى مشروع" />
            @endforelse
        </div>

        <div class="panel">
            <h2>آخر الفواتير <a href="{{ route('invoices.index') }}">الكل</a></h2>
            @forelse ($latestInvoices as $invoice)
                @if ($loop->first)<div class="list">@endif
                @php($state = $invoice->displayState())
                <div class="row">
                    <div class="grow">
                        <div class="t num" style="text-align:right"><a href="{{ route('invoices.show', $invoice) }}" style="text-decoration:none">{{ $invoice->reference() }}</a></div>
                        <div class="s">{{ $invoice->client->displayName() }}</div>
                    </div>
                    <x-money :amount="$invoice->total()" />
                    <x-pill :label="$state['label']" :tone="$state['class']" />
                </div>
                @if ($loop->last)</div>@endif
            @empty
                <x-empty title="لا فواتير بعد" hint="أنشئ أول فاتورة لعميلك" />
            @endforelse
        </div>
    </div>
</x-layouts.app>
