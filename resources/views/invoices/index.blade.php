<x-layouts.app title="الفواتير">
    <x-page-header title="الفواتير" subtitle="فواتير بضريبة القيمة المضافة 15٪">
        <a class="btn pri" href="{{ route('invoices.create') }}">+ فاتورة جديدة</a>
    </x-page-header>

    <div class="kpis" style="grid-template-columns:repeat(3,minmax(0,1fr))">
        <div class="kpi"><span>المحصّل</span><strong><x-money :amount="$collected" /></strong></div>
        <div class="kpi"><span>بانتظار الدفع</span><strong><x-money :amount="$awaiting" /></strong></div>
        <div class="kpi"><span>عدد الفواتير</span><strong class="num">{{ $count }}</strong></div>
    </div>

    <div class="toolbar">
        <form method="GET" action="{{ route('invoices.index') }}">
            <select name="state" data-autosubmit aria-label="تصفية حسب الحالة">
                <option value="">الكل</option>
                @foreach (['due' => 'مستحقة', 'late' => 'متأخرة', 'paid' => 'مدفوعة', 'draft' => 'مسودة'] as $key => $label)
                    <option value="{{ $key }}" @selected($state === $key)>{{ $label }}</option>
                @endforeach
            </select>
            <noscript><button class="btn" type="submit">تصفية</button></noscript>
        </form>
    </div>

    @if ($invoices->isNotEmpty())
        <div class="tbl-wrap">
            <table>
                <thead><tr><th>الرقم</th><th>العميل</th><th>التاريخ</th><th>الاستحقاق</th><th>الإجمالي</th><th>الحالة</th><th></th></tr></thead>
                <tbody>
                    @foreach ($invoices as $invoice)
                        @php($state = $invoice->displayState())
                        <tr>
                            <td class="num">{{ $invoice->reference() }}</td>
                            <td>{{ $invoice->client->displayName() }}</td>
                            <td>{{ $invoice->issued_on->translatedFormat('j F') }}</td>
                            <td>{{ $invoice->due_on?->translatedFormat('j F') ?? '—' }}</td>
                            <td><x-money :amount="$invoice->total()" /></td>
                            <td><x-pill :label="$state['label']" :tone="$state['class']" /></td>
                            <td>
                                <div class="act">
                                    <a class="btn sm" href="{{ route('invoices.show', $invoice) }}">عرض</a>
                                    <a class="btn sm" href="{{ route('invoices.edit', $invoice) }}">تعديل</a>
                                    @if ($invoice->status !== \App\Enums\InvoiceStatus::Paid)
                                        <form method="POST" action="{{ route('invoices.payment.store', $invoice) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="btn sm pri">تم الدفع</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="panel">
            <x-empty title="لا فواتير هنا" hint="أنشئ فاتورة واربطها بعميل أو مشروع">
                <a class="btn pri" href="{{ route('invoices.create') }}">+ فاتورة جديدة</a>
            </x-empty>
        </div>
    @endif
</x-layouts.app>
