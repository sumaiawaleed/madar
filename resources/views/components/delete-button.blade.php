@props(['action', 'label' => 'حذف'])
{{-- Rendered outside the edit form; the button joins it through the form attribute. --}}
<form method="POST" action="{{ $action }}" id="delete-record" hidden>
    @csrf
    @method('DELETE')
</form>
<button type="submit" form="delete-record" class="btn danger" data-label="{{ $label }}" data-confirm="اضغط لتأكيد الحذف">{{ $label }}</button>
