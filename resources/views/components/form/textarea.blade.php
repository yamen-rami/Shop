@props(['feild' , "type" , "value" , "edit"=> null, 'stacked' => false])
<div class="{{ $stacked ? 'product-form-field' : 'row mb-6' }}">
  <label class="{{ $stacked ? 'form-label' : 'col-sm-2 col-form-label' }}" for="{{ $attributes->get('id', 'field-' . $feild) }}">{{ $value }}</label>
  <div class="{{ $stacked ? '' : 'col-sm-10' }}">
    <textarea 
    {{ $attributes->merge(['class' => "form-control" , "id" => 'field-' . $feild]) }}
    placeholder="Enter {{ $feild }}"
    name="{{ $feild }}"
    >{{ old($feild, $edit) }}</textarea>
    @error($feild)
      <p class="text-danger">{{ $message }}</p>
    @enderror
  </div>
</div>
