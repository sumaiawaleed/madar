@php($editing = $client->exists)
<x-layouts.app :title="$editing ? 'تعديل عميل' : 'عميل جديد'">
    <x-page-header :title="$editing ? 'تعديل عميل' : 'عميل جديد'">
        <a class="btn" href="{{ route('clients.index') }}">رجوع</a>
    </x-page-header>

    <div class="form-page">
        <x-form-errors />
        <form class="panel" method="POST" enctype="multipart/form-data" action="{{ $editing ? route('clients.update', $client) : route('clients.store') }}">
            @csrf
            @if ($editing) @method('PUT') @endif
            <div class="dlg-b">
                <x-logo-picker label="شعار العميل" :path="$client->logo_path" />
                <x-field name="name" label="اسم العميل" :value="$client->name" required />
                <x-field name="company" label="الشركة / النشاط" :value="$client->company" />
                <x-field name="phone" label="الجوال" type="tel" :value="$client->phone" />
                <x-field name="email" label="البريد" type="email" :value="$client->email" />
                <x-field name="notes" label="ملاحظات" type="textarea" :value="$client->notes" full />
            </div>
            <div class="dlg-f">
                <button class="btn pri" type="submit">حفظ</button>
                <a class="btn" href="{{ route('clients.index') }}">إلغاء</a>
                <span class="sp"></span>
                @if ($editing)<x-delete-button :action="route('clients.destroy', $client)" />@endif
            </div>
        </form>
    </div>
</x-layouts.app>
