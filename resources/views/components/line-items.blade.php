@props(['items', 'vatToggle' => null])
@php($rows = old('items', $items ?: [['description' => '', 'quantity' => 1, 'price' => 0]]))
<div class="fld full" data-line-items @if ($vatToggle) data-vat-toggle="{{ $vatToggle }}" @endif>
    <label>البنود</label>
    <div class="items">
        @foreach ($rows as $item)
            <div class="item">
                <input data-name="description" placeholder="الوصف" aria-label="الوصف" value="{{ $item['description'] ?? '' }}">
                <input data-name="quantity" type="number" step="any" min="0" class="num" aria-label="الكمية" value="{{ $item['quantity'] ?? 1 }}">
                <input data-name="price" type="number" step="any" min="0" class="num" aria-label="السعر" value="{{ $item['price'] ?? 0 }}">
                <button type="button" class="btn sm ghost" data-remove-item aria-label="حذف البند">✕</button>
            </div>
        @endforeach
    </div>
    <template>
        <div class="item">
            <input data-name="description" placeholder="الوصف" aria-label="الوصف">
            <input data-name="quantity" type="number" step="any" min="0" class="num" aria-label="الكمية" value="1">
            <input data-name="price" type="number" step="any" min="0" class="num" aria-label="السعر" value="0">
            <button type="button" class="btn sm ghost" data-remove-item aria-label="حذف البند">✕</button>
        </div>
    </template>
    <button type="button" class="btn sm" data-add-item style="align-self:flex-start;margin-top:4px">+ بند</button>
    <div class="totals"></div>
    @error('items')<span class="err">{{ $message }}</span>@enderror
    @if ($errors->has('items.*'))<span class="err">{{ $errors->first('items.*') }}</span>@endif
</div>
