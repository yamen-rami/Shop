@props(['resource', 'selected' => [], 'field' => null, 'placeholder' => 'Search…'])
@php(
    $selectedIds = collect(is_array($selected) || $selected instanceof \Illuminate\Support\Collection ? $selected : [$selected])->filter(fn($id) => $id !== null && $id !== '')->unique()
)
<select data-user-select2 data-select2-url="{{ route('select-options', $resource) }}"
    data-placeholder="{{ $placeholder }}"
    @if ($field) data-livewire-field="{{ $field }}" @endif
    {{ $attributes->merge(['class' => 'select2 form-select']) }}>
    @unless ($attributes->has('multiple'))
        <option value="">{{ $placeholder }}</option>
    @endunless
    @foreach ($selectedIds as $id)
        <option value="{{ $id }}" selected>#{{ $id }}</option>
    @endforeach
</select>
