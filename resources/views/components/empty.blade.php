@props(['title', 'hint'])
<div class="empty"><b>{{ $title }}</b>{{ $hint }}@if ($slot->isNotEmpty())<div style="margin-top:12px">{{ $slot }}</div>@endif</div>
