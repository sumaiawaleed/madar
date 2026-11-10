<x-layouts.app title="الأرباح">
    <x-page-header title="الأرباح" subtitle="دخلك من الفواتير المدفوعة" />

    <div class="kpis">
        <div class="kpi"><span>آخر ١٢ شهرًا</span><strong><x-money :amount="$yearTotal" /></strong><small>قبل الضريبة</small></div>
        <div class="kpi"><span>متوسط شهري</span><strong><x-money :amount="$monthlyAverage" /></strong></div>
        <div class="kpi"><span>أفضل شهر</span><strong>{{ $bestMonth['total'] ? $bestMonth['label'] : '—' }}</strong><small><x-money :amount="$bestMonth['total']" /></small></div>
        <div class="kpi"><span>ضريبة محصّلة</span><strong><x-money :amount="$vatCollected" /></strong><small>15٪ قيمة مضافة</small></div>
    </div>

    <div class="grid2">
        <div class="panel">
            <h2>الدخل الشهري</h2>
            <x-bar-chart :data="$months" />
        </div>
        <div class="panel">
            <h2>حسب العميل</h2>
            @if ($byClient->isNotEmpty())
                <div class="mini-bars">
                    @foreach ($byClient as $row)
                        <div class="r">
                            <span>{{ $row['name'] }}</span>
                            <div class="bar"><i style="width:{{ round($row['total'] / $byClientMax * 100, 1) }}%"></i></div>
                            <b style="font-size:13px"><x-money :amount="$row['total']" /></b>
                        </div>
                    @endforeach
                </div>
            @else
                <x-empty title="لا دخل بعد" hint="علّم الفاتورة كمدفوعة لتظهر هنا" />
            @endif
        </div>
    </div>
</x-layouts.app>
