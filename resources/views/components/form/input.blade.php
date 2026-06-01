@props(['feild', "type" , "value" , "edit"=> null])
<div class="row mb-6">
  <label class="col-sm-2 col-form-label" for="basic-default-name">{{ $value ?? "name" }}</label>
  <div class="col-sm-10">
    <input
     {{ $attributes->merge(["class" => "form-control", "id" => "basic-default-name"]) }} 
      name="{{ $feild }}"
      type="{{ $type }}"
      value="{{ $edit }}"
      placeholder="Enter {{ $feild }}" />
      @error($feild)
        <p class="text-danger">{{ $message }}</p>
      @enderror
  </div>
</div>