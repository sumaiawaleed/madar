@props(['amount'])
@php($value = round((float) $amount, 2))
<span {{ $attributes->merge(['class' => 'num']) }}><x-sar />{{ fmod($value, 1) == 0 ? number_format($value) : rtrim(number_format($value, 2), '0') }}</span>
