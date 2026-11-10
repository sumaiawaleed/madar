@php($editing = $proposal->exists)
<x-layouts.app :title="$editing ? 'تعديل عرض' : 'عرض سعر جديد'">
    <x-page-header :title="$editing ? 'تعديل '.$proposal->reference() : 'عرض سعر جديد'">
        <a class="btn" href="{{ route('proposals.index') }}">رجوع</a>
    </x-page-header>

    <div class="form-page">
        <x-form-errors />
        <form class="panel" method="POST" action="{{ $editing ? route('proposals.update', $proposal) : route('proposals.store') }}">
            @csrf
            @if ($editing) @method('PUT') @endif
            <div class="dlg-b">
                <x-field name="title" label="عنوان العرض" :value="$proposal->title" required full />
                <x-field name="client_id" label="العميل" type="select" required>
                    <x-client-options :clients="$clients" :selected="old('client_id', $proposal->client_id)" />
                </x-field>
                <x-field name="status" label="الحالة" type="select">
                    @foreach (\App\Enums\ProposalStatus::cases() as $case)
                        <option value="{{ $case->value }}" @selected(old('status', $proposal->status?->value) === $case->value)>{{ $case->label() }}</option>
                    @endforeach
                </x-field>
                <x-field name="issued_on" label="التاريخ" type="date" :value="$proposal->issued_on?->toDateString()" required />
                <x-field name="valid_until" label="صالح حتى" type="date" :value="$proposal->valid_until?->toDateString()" />
                <x-line-items :items="$proposal->items" />
                <x-field name="notes" label="شروط وملاحظات" type="textarea" :value="$proposal->notes" full />
            </div>
            <div class="dlg-f">
                <button class="btn pri" type="submit">حفظ</button>
                <a class="btn" href="{{ route('proposals.index') }}">إلغاء</a>
                <span class="sp"></span>
                @if ($editing)<x-delete-button :action="route('proposals.destroy', $proposal)" />@endif
            </div>
        </form>
    </div>
</x-layouts.app>
