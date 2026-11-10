<x-layouts.app title="العملاء">
    <x-page-header title="العملاء" :subtitle="$totalCount.' عميل'">
        <a class="btn pri" href="{{ route('clients.create') }}">+ عميل جديد</a>
    </x-page-header>

    <div class="toolbar">
        <form method="GET" action="{{ route('clients.index') }}" role="search">
            <input name="q" type="search" placeholder="ابحث بالاسم أو الشركة" value="{{ $search }}" aria-label="بحث" @if ($search) autofocus @endif>
            <button type="submit" class="btn">بحث</button>
        </form>
    </div>

    @if ($clients->isNotEmpty())
        <div class="clients">
            @foreach ($clients as $client)
                <div class="client">
                    <header>
                        <div class="avatar {{ $client->logo_path ? 'has-logo' : '' }}">
                            @if ($client->logo_path)<img src="{{ Storage::disk('public')->url($client->logo_path) }}" alt="">@else{{ $client->initial() }}@endif
                        </div>
                        <div style="min-width:0">
                            <div class="t" style="font-weight:600">{{ $client->name }}</div>
                            <div class="s" style="color:var(--muted);font-size:13px">{{ $client->company ?: '—' }}</div>
                        </div>
                    </header>
                    <div class="s" style="font-size:13px;color:var(--muted)">
                        @if ($client->phone)<span class="num">{{ $client->phone }}</span>@endif{{ $client->phone && $client->email ? ' · ' : '' }}{{ $client->email }}
                    </div>
                    <div class="stats">
                        <div><b class="num">{{ $client->projects_count }}</b>مشاريع</div>
                        <div><b><x-money :amount="$client->paid_total" /></b>إجمالي المدفوع</div>
                    </div>
                    <footer>
                        <a class="btn sm" href="{{ route('clients.edit', $client) }}">تعديل</a>
                        <a class="btn sm" href="{{ route('invoices.create', ['client' => $client->id]) }}">فاتورة</a>
                        <a class="btn sm" href="{{ route('proposals.create', ['client' => $client->id]) }}">عرض</a>
                    </footer>
                </div>
            @endforeach
        </div>
    @else
        <div class="panel">
            <x-empty :title="$search ? 'لا نتائج' : 'لا عملاء بعد'" :hint="$search ? 'جرّب كلمة أخرى' : 'أضف أول عميل لتبدأ بإرسال العروض والفواتير'">
                <a class="btn pri" href="{{ route('clients.create') }}">+ عميل جديد</a>
            </x-empty>
        </div>
    @endif
</x-layouts.app>
