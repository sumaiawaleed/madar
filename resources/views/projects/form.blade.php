@php($editing = $project->exists)
<x-layouts.app :title="$editing ? 'تعديل مشروع' : 'مشروع جديد'">
    <x-page-header :title="$editing ? 'تعديل مشروع' : 'مشروع جديد'">
        <a class="btn" href="{{ route('projects.index') }}">رجوع</a>
    </x-page-header>

    <div class="form-page">
        <x-form-errors />
        <form class="panel" method="POST" action="{{ $editing ? route('projects.update', $project) : route('projects.store') }}">
            @csrf
            @if ($editing) @method('PUT') @endif
            @if ($project->proposal_id)<input type="hidden" name="proposal_id" value="{{ $project->proposal_id }}">@endif
            <div class="dlg-b">
                <x-field name="title" label="اسم المشروع" :value="$project->title" required full />
                <x-field name="client_id" label="العميل" type="select">
                    <x-client-options :clients="$clients" :selected="old('client_id', $project->client_id)" />
                </x-field>
                <x-field name="status" label="المرحلة" type="select">
                    @foreach (\App\Enums\ProjectStatus::cases() as $case)
                        <option value="{{ $case->value }}" @selected(old('status', $project->status?->value) === $case->value)>{{ $case->label() }}</option>
                    @endforeach
                </x-field>
                <x-field name="budget" label="الميزانية (بالريال)" type="number" min="0" :value="$project->budget !== null ? (float) $project->budget : null" />
                <x-field name="progress" label="نسبة الإنجاز %" type="number" min="0" max="100" :value="$project->progress" />
                <x-field name="starts_on" label="البداية" type="date" :value="$project->starts_on?->toDateString()" />
                <x-field name="due_on" label="موعد التسليم" type="date" :value="$project->due_on?->toDateString()" />
                <x-field name="notes" label="ملاحظات" type="textarea" :value="$project->notes" full />
            </div>
            <div class="dlg-f">
                <button class="btn pri" type="submit">حفظ</button>
                <a class="btn" href="{{ route('projects.index') }}">إلغاء</a>
                <span class="sp"></span>
                @if ($editing)<x-delete-button :action="route('projects.destroy', $project)" />@endif
            </div>
        </form>
    </div>
</x-layouts.app>
