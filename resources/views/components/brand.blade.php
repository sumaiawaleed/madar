@props(['tagline' => 'مساحة عمل المستقل'])
<a href="{{ route('home') }}" class="brand" style="text-decoration:none">
    <img src="{{ asset('images/madar-logo.png') }}" width="40" height="40" alt="" style="flex:none">
    <div><b>مـــدار</b>@if ($tagline)<small>{{ $tagline }}</small>@endif</div>
</a>
