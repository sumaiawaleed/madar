@php($editing = $appointment->exists)
<x-layouts.app :title="$editing ? 'تعديل موعد' : 'موعد جديد'">
    <x-page-header :title="$editing ? 'تعديل موعد' : 'موعد جديد'">
        <a class="btn" href="{{ route('appointments.index') }}">رجوع</a>
    </x-page-header>

    <div class="form-page">
        <x-form-errors />
        <form class="panel" method="POST" action="{{ $editing ? route('appointments.update', $appointment) : route('appointments.store') }}">
            @csrf
            @if ($editing) @method('PUT') @endif
            <div class="dlg-b">
                <x-field name="title" label="عنوان الموعد" :value="$appointment->title" required full />
                <x-field name="client_id" label="العميل" type="select">
                    <x-client-options :clients="$clients" :selected="old('client_id', $appointment->client_id)" />
                </x-field>
                <x-field name="type" label="النوع" type="select">
                    @foreach (\App\Models\Appointment::TYPES as $type)
                        <option value="{{ $type }}" @selected(old('type', $appointment->type) === $type)>{{ $type }}</option>
                    @endforeach
                </x-field>
                <x-field name="scheduled_on" label="التاريخ" type="date" :value="$appointment->scheduled_on?->toDateString()" required />
                <x-field name="starts_at" label="الوقت" type="time" :value="$appointment->startTime()" required />
                <x-field name="duration_minutes" label="المدة (دقيقة)" type="number" min="5" :value="$appointment->duration_minutes" />
                <x-field name="location" label="المكان أو الرابط" :value="$appointment->location" />
            </div>
            <div class="dlg-f">
                <button class="btn pri" type="submit">حفظ</button>
                <a class="btn" href="{{ route('appointments.index') }}">إلغاء</a>
                <span class="sp"></span>
                @if ($editing)<x-delete-button :action="route('appointments.destroy', $appointment)" />@endif
            </div>
        </form>
    </div>
</x-layouts.app>
