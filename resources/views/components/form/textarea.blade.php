@props(['feild' , "type" , "value" , "edit"=> null])
<div class="row mb-6">
  <label class="col-sm-2 col-form-label" for="basic-default-message">{{ $value }}</label>
  <div class="col-sm-10">
    <textarea 
    {{ $attributes->merge(['class' => "form-control" , "id" => "basic-default-message"]) }}
    placeholder="Enter {{ $feild }}"
    name="{{ $feild }}"
    >{{ $edit }}</textarea>
    @error($feild)
      <p class="text-danger">{{ $message }}</p>
    @enderror
  </div>
</div>