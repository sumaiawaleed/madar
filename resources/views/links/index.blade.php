<x-layouts.app title="صفحة الروابط">
    <x-page-header title="صفحة الروابط" subtitle="صفحة واحدة تجمع أعمالك وطرق التواصل معك">
        @if ($user->handle)
            <a class="btn" href="{{ route('link-page.show', $user->handle) }}" target="_blank" rel="noopener">فتح صفحتي</a>
        @endif
        <a class="btn pri" href="{{ route('links.create') }}">+ رابط</a>
    </x-page-header>

    <div class="links-layout">
        <div class="panel">
            <h2>الروابط</h2>
            @forelse ($links as $link)
                @if ($loop->first)<div class="list">@endif
                <div class="row">
                    <div class="grow">
                        <div class="t">{{ $link->title }}</div>
                        <div class="s num" style="text-align:right;overflow:hidden;text-overflow:ellipsis">{{ $link->url }}</div>
                    </div>
                    <form method="POST" action="{{ route('links.position.update', $link) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn sm ghost" aria-label="تحريك للأعلى" @disabled($loop->first)>↑</button>
                    </form>
                    <a class="btn sm" href="{{ route('links.edit', $link) }}">تعديل</a>
                </div>
                @if ($loop->last)</div>@endif
            @empty
                <x-empty title="لا روابط" hint="أضف رابط معرض أعمالك أو واتساب" />
            @endforelse
            <p style="color:var(--muted);font-size:13px;margin:14px 0 0">
                الاسم والنبذة واسم المستخدم تُعدّل من <a href="{{ route('profile.edit') }}" style="color:var(--accent)">الإعدادات</a>.
                @if ($user->handle)
                    رابط صفحتك: <a class="num" href="{{ route('link-page.show', $user->handle) }}" style="color:var(--accent)">{{ route('link-page.show', $user->handle) }}</a>
                @endif
            </p>
        </div>
        @include('links._phone', ['user' => $user, 'links' => $links])
    </div>
</x-layouts.app>
