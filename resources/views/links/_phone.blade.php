<div class="phone">
    <div class="avatar {{ $user->logo_path ? 'has-logo' : '' }}">
        @if ($user->logo_path)<img src="{{ Storage::disk('public')->url($user->logo_path) }}" alt="">@else{{ $user->initial() }}@endif
    </div>
    <h3>{{ $user->name ?: 'اسمك' }}</h3>
    <p>{{ $user->job_title }}</p>
    <p>{{ $user->bio }}</p>
    <div style="display:flex;flex-direction:column;gap:10px;width:100%;margin-top:8px">
        @foreach ($links as $link)
            <a href="{{ $link->href() }}" target="_blank" rel="noopener nofollow">{{ $link->title }}</a>
        @endforeach
    </div>
    <div class="foot">{{ $user->handle ? '@'.$user->handle.' · ' : '' }}<a href="{{ route('home') }}" style="display:inline;padding:0;border:0;box-shadow:none;background:none;color:inherit;font-weight:400;width:auto">مدار</a></div>
</div>
