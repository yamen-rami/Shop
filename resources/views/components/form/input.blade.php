@props(['feild', "type" , "value" , "edit"=> null])
<div class="row mb-6">
  <label class="col-sm-2 col-form-label" for="{{ $attributes->get('id', 'field-' . $feild) }}">{{ $value ?? "name" }}</label>
  <div class="col-sm-10">
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
