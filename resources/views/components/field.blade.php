@props(['name', 'label', 'type' => 'text', 'value' => null, 'full' => false, 'required' => false])
@php($id = 'f_'.str_replace(['[', ']', '.'], '_', $name))
<div class="fld {{ $full ? 'full' : '' }}">
    <label for="{{ $id }}">{{ $label }}{{ $required ? ' *' : '' }}</label>
    @if ($type === 'textarea')
        <textarea id="{{ $id }}" name="{{ $name }}" @error($name) aria-invalid="true" @enderror {{ $attributes }}>{{ old($name, $value) }}</textarea>
    @elseif ($type === 'select')
        <select id="{{ $id }}" name="{{ $name }}" @error($name) aria-invalid="true" @enderror {{ $attributes }}>{{ $slot }}</select>
    @else
        <input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $value) }}" @if ($required) required @endif @if ($type === 'number') step="any" @endif @error($name) aria-invalid="true" @enderror {{ $attributes }}>
    @endif
    @error($name)<span class="err">{{ $message }}</span>@enderror
</div>
