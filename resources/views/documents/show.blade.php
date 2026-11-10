@php
    $owner = $document->user;
    $client = $document->client;
    $subtotal = $document->subtotal();
    $vat = $isInvoice ? $document->vatAmount() : 0;
    $backRoute = $isInvoice ? 'invoices.index' : 'proposals.index';
    $editRoute = $isInvoice ? 'invoices.edit' : 'proposals.edit';
@endphp
<x-layouts.app :title="$document->reference()">
    <x-page-header :title="$document->reference()">
        <button type="button" class="btn pri" data-print>طباعة / حفظ PDF</button>
        <a class="btn" href="{{ route($editRoute, $document) }}">تعديل</a>
        <a class="btn" href="{{ route($backRoute) }}">رجوع</a>
    </x-page-header>

    <div class="doc-page">
        <div class="doc">
            <div class="hd">
                <div>
                    <h3>{{ $isInvoice ? ($document->has_vat ? 'فاتورة ضريبية' : 'فاتورة') : 'عرض سعر' }}</h3>
                    <div class="num" style="color:var(--muted);text-align:right">{{ $document->reference() }}</div>
                </div>
                <div style="text-align:left">
                    @if ($owner->logo_path)<img src="{{ Storage::disk('public')->url($owner->logo_path) }}" alt="" style="max-height:60px;max-width:150px;display:block;margin-right:auto;margin-bottom:8px">@endif
                    <b>{{ $owner->business_name ?: $owner->name }}</b>
                    <div style="font-size:13px;color:var(--muted)">
                        {{ $owner->email }}
                        @if ($owner->vat_number)<br>الرقم الضريبي <span class="num">{{ $owner->vat_number }}</span>@endif
                    </div>
                </div>
            </div>

            <div class="meta">
                <div>
                    <span>إلى</span>
                    @if ($client->logo_path)<img src="{{ Storage::disk('public')->url($client->logo_path) }}" alt="" style="max-height:42px;max-width:120px;display:block;margin-bottom:6px">@endif
                    {{ $client->name }}@if ($client->company)<br>{{ $client->company }}@endif
                </div>
                <div>
                    <span>{{ $isInvoice ? 'تاريخ الإصدار / الاستحقاق' : 'التاريخ / صالح حتى' }}</span>
                    {{ $document->issued_on->translatedFormat('j F Y') }} — {{ ($isInvoice ? $document->due_on : $document->valid_until)?->translatedFormat('j F Y') ?? '—' }}
                </div>
                @unless ($isInvoice)
                    <div style="grid-column:1/-1"><span>الموضوع</span>{{ $document->title }}</div>
                @endunless
            </div>

            <table>
                <thead><tr><th>البند</th><th>الكمية</th><th>السعر</th><th>المجموع</th></tr></thead>
                <tbody>
                    @foreach ($document->items as $item)
                        <tr>
                            <td>{{ $item['description'] }}</td>
                            <td class="num">{{ $item['quantity'] + 0 }}</td>
                            <td><x-money :amount="$item['price']" /></td>
                            <td><x-money :amount="$item['quantity'] * $item['price']" /></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="totals">
                <span>المجموع <x-money :amount="$subtotal" /></span>
                @if ($vat)<span>ضريبة القيمة المضافة 15٪ <x-money :amount="$vat" /></span>@endif
                <b>الإجمالي <x-money :amount="$subtotal + $vat" /></b>
            </div>

            @if (! $isInvoice && $document->notes)
                <div style="font-size:14px;color:var(--muted);white-space:pre-line">{{ $document->notes }}</div>
            @endif
            @if ($isInvoice && $owner->iban)
                <div style="font-size:13px;color:var(--muted)">التحويل إلى الآيبان <span class="num">{{ $owner->iban }}</span></div>
            @endif
        </div>
    </div>
</x-layouts.app>
