@props(['feild', "type" , "value" , "edit"=> null, 'stacked' => false])
<div class="{{ $stacked ? 'product-form-field' : 'row mb-6' }}">
  <label class="{{ $stacked ? 'form-label' : 'col-sm-2 col-form-label' }}" for="{{ $attributes->get('id', 'field-' . $feild) }}">{{ $value ?? "name" }}</label>
  <div class="{{ $stacked ? '' : 'col-sm-10' }}">
    <input
     {{ $attributes->merge(["class" => "form-control", "id" => 'field-' . $feild]) }}
      name="{{ $feild }}"
      type="{{ $type }}"
      @if($type !== 'file') value="{{ old($feild, $edit) }}" @endif
      placeholder="Enter {{ $feild }}" />
      @error($feild)
        <p class="text-danger">{{ $message }}</p>
      @enderror
  </div>
</div>
