<x-layouts.app title="عروض الأسعار">
    <x-page-header title="عروض الأسعار" subtitle="أرسل عرضك، وحوّل المقبول إلى مشروع بضغطة">
        <a class="btn pri" href="{{ route('proposals.create') }}">+ عرض جديد</a>
    </x-page-header>

    <div class="toolbar">
        <form method="GET" action="{{ route('proposals.index') }}">
            <select name="status" data-autosubmit aria-label="تصفية حسب الحالة">
                <option value="">كل العروض</option>
                @foreach (\App\Enums\ProposalStatus::cases() as $case)
                    <option value="{{ $case->value }}" @selected($status === $case)>{{ $case->label() }}</option>
                @endforeach
            </select>
            <noscript><button class="btn" type="submit">تصفية</button></noscript>
        </form>
    </div>

    @if ($proposals->isNotEmpty())
        <div class="tbl-wrap">
            <table>
                <thead><tr><th>الرقم</th><th>العنوان</th><th>العميل</th><th>القيمة</th><th>صالح حتى</th><th>الحالة</th><th></th></tr></thead>
                <tbody>
                    @foreach ($proposals as $proposal)
                        <tr>
                            <td class="num">{{ $proposal->reference() }}</td>
                            <td>{{ $proposal->title }}</td>
                            <td>{{ $proposal->client->displayName() }}</td>
                            <td><x-money :amount="$proposal->subtotal()" /></td>
                            <td>{{ $proposal->valid_until?->translatedFormat('j F') ?? '—' }}</td>
                            <td><x-pill :label="$proposal->status->label()" :tone="$proposal->status->pillClass()" /></td>
                            <td>
                                <div class="act">
                                    <a class="btn sm" href="{{ route('proposals.show', $proposal) }}">عرض</a>
                                    <a class="btn sm" href="{{ route('proposals.edit', $proposal) }}">تعديل</a>
                                    @if ($proposal->canBecomeProject())
                                        <a class="btn sm pri" href="{{ route('projects.create', ['proposal' => $proposal->id]) }}">تحويل لمشروع</a>
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
            <x-empty title="لا عروض هنا" hint="اكتب عرض سعر مفصّل بالبنود وأرسله لعميلك">
                <a class="btn pri" href="{{ route('proposals.create') }}">+ عرض جديد</a>
            </x-empty>
        </div>
    @endif
</x-layouts.app>
