@props(['title' => null])
<x-layouts.base :title="$title">
    <div class="app">
        <aside class="rail">
            <x-brand />
            <nav aria-label="القائمة الرئيسية">
                @foreach ($navigation as $item)
                    <a href="{{ route($item['route']) }}" @if (request()->routeIs($item['active'])) aria-current="page" @endif>
                        <x-icon :name="$item['icon']" />{{ $item['label'] }}
                        @if ($item['count'])<span class="cnt num">{{ $item['count'] }}</span>@endif
                    </a>
                @endforeach
            </nav>
            <div class="me">
                <b>{{ auth()->user()->name }}</b>{{ auth()->user()->job_title ?: auth()->user()->business_name }}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn sm">تسجيل الخروج</button>
                </form>
            </div>
        </aside>
        <main>
            {{ $slot }}
        </main>
    </div>
</x-layouts.base>
