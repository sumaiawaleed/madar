@php($editing = $link->exists)
<x-layouts.app :title="$editing ? 'تعديل رابط' : 'رابط جديد'">
    <x-page-header :title="$editing ? 'تعديل رابط' : 'رابط جديد'">
        <a class="btn" href="{{ route('links.index') }}">رجوع</a>
    </x-page-header>

    <div class="form-page">
        <x-form-errors />
        <form class="panel" method="POST" action="{{ $editing ? route('links.update', $link) : route('links.store') }}">
            @csrf
            @if ($editing) @method('PUT') @endif
            <div class="dlg-b">
                <x-field name="title" label="النص الظاهر" :value="$link->title" required full />
                <x-field name="url" label="الرابط" :value="$link->url" required full dir="ltr" placeholder="https://" />
            </div>
            <div class="dlg-f">
                <button class="btn pri" type="submit">حفظ</button>
                <a class="btn" href="{{ route('links.index') }}">إلغاء</a>
                <span class="sp"></span>
                @if ($editing)<x-delete-button :action="route('links.destroy', $link)" />@endif
            </div>
        </form>
    </div>
</x-layouts.app>
