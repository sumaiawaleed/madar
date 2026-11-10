<x-layouts.app title="المشاريع">
    <x-page-header title="المشاريع" subtitle="تابع كل مشروع من البداية حتى التسليم">
        <a class="btn pri" href="{{ route('projects.create') }}">+ مشروع</a>
    </x-page-header>

    <div class="board">
        @foreach ($columns as $column)
            <div class="col">
                <h3><span>{{ $column['status']->label() }}</span><span class="num">{{ $column['projects']->count() }}</span></h3>
                @forelse ($column['projects'] as $project)
                    <div class="card">
                        <div class="t">{{ $project->title }}</div>
                        <div class="meta"><span>{{ $project->client?->displayName() ?? 'بدون عميل' }}</span><x-money :amount="$project->budget" /></div>
                        <div class="bar"><i style="width:{{ $project->progress }}%"></i></div>
                        <div class="meta">
                            <span @if ($project->isOverdue()) style="color:var(--red);font-weight:600" @endif>{{ $project->isOverdue() ? 'متأخر · ' : '' }}التسليم {{ $project->due_on?->translatedFormat('j F') ?? '—' }}</span>
                            <span class="num">{{ $project->progress }}%</span>
                        </div>
                        <div class="meta">
                            <form method="POST" action="{{ route('projects.stage.update', $project) }}">
                                @csrf
                                @method('PATCH')
                                <select name="status" data-autosubmit aria-label="نقل المشروع">
                                    @foreach (\App\Enums\ProjectStatus::cases() as $case)
                                        <option value="{{ $case->value }}" @selected($case === $project->status)>{{ $case->label() }}</option>
                                    @endforeach
                                </select>
                                <noscript><button class="btn sm" type="submit">نقل</button></noscript>
                            </form>
                            <span>
                                <a class="btn sm ghost" href="{{ route('projects.edit', $project) }}">تعديل</a>
                                @if ($project->client_id)
                                    <a class="btn sm ghost" href="{{ route('invoices.create', ['project' => $project->id]) }}">فاتورة</a>
                                @endif
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="s" style="color:var(--muted);font-size:13px;text-align:center;padding:12px">لا شيء هنا</div>
                @endforelse
            </div>
        @endforeach
    </div>
</x-layouts.app>
