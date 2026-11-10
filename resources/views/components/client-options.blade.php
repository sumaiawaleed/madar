@props(['clients', 'selected' => null])
<option value="">— اختر عميلًا —</option>
@foreach ($clients as $client)
    <option value="{{ $client->id }}" @selected((string) $selected === (string) $client->id)>{{ $client->company ? $client->company.' · '.$client->name : $client->name }}</option>
@endforeach
