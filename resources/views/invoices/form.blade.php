@php($editing = $invoice->exists)
<x-layouts.app :title="$editing ? 'تعديل فاتورة' : 'فاتورة جديدة'">
    <x-page-header :title="$editing ? 'تعديل '.$invoice->reference() : 'فاتورة جديدة'">
        <a class="btn" href="{{ route('invoices.index') }}">رجوع</a>
    </x-page-header>

    <div class="form-page">
        <x-form-errors />
        <form class="panel" method="POST" action="{{ $editing ? route('invoices.update', $invoice) : route('invoices.store') }}">
            @csrf
            @if ($editing) @method('PUT') @endif
            @if ($invoice->project_id)<input type="hidden" name="project_id" value="{{ $invoice->project_id }}">@endif
            <div class="dlg-b">
                <x-field name="client_id" label="العميل" type="select" required>
                    <x-client-options :clients="$clients" :selected="old('client_id', $invoice->client_id)" />
                </x-field>
                <x-field name="status" label="الحالة" type="select">
                    @foreach (\App\Enums\InvoiceStatus::cases() as $case)
                        <option value="{{ $case->value }}" @selected(old('status', $invoice->status?->value) === $case->value)>{{ $case->label() }}</option>
                    @endforeach
                </x-field>
                <x-field name="issued_on" label="تاريخ الإصدار" type="date" :value="$invoice->issued_on?->toDateString()" required />
                <x-field name="due_on" label="تاريخ الاستحقاق" type="date" :value="$invoice->due_on?->toDateString()" />
                <div class="fld full">
                    <label class="check"><input type="checkbox" id="has_vat" name="has_vat" value="1" @checked(old('has_vat', $invoice->has_vat))> إضافة ضريبة القيمة المضافة 15٪</label>
                </div>
                <x-line-items :items="$invoice->items" vat-toggle="has_vat" />
            </div>
            <div class="dlg-f">
                <button class="btn pri" type="submit">حفظ</button>
                <a class="btn" href="{{ route('invoices.index') }}">إلغاء</a>
                <span class="sp"></span>
                @if ($editing)<x-delete-button :action="route('invoices.destroy', $invoice)" />@endif
            </div>
        </form>
    </div>
</x-layouts.app>
