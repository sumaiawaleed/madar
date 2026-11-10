@props(['path' => null, 'label' => 'الشعار'])
<div class="fld full">
    <label>{{ $label }}</label>
    <div class="logo-pick">
        <div class="logo-box" id="logo-prev">
            @if ($path)<img src="{{ Storage::disk('public')->url($path) }}" alt="">@else<span>بدون شعار</span>@endif
        </div>
        <label class="btn sm" for="f_logo">{{ $path ? 'تغيير الشعار' : 'رفع شعار' }}</label>
        <input type="file" id="f_logo" name="logo" accept="image/png,image/jpeg,image/webp" hidden data-logo-input="logo-prev">
        @if ($path)
            <label class="check" style="font-size:13px"><input type="checkbox" name="remove_logo" value="1"> إزالة الشعار</label>
        @endif
    </div>
    @error('logo')<span class="err">{{ $message }}</span>@enderror
</div>
