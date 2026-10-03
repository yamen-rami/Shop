@section("title")
  Edit Product {{ $product->name }}
@endsection
<x-main-layout>

  <x-slot:header>
    Editing {{ $product->name }}
  </x-slot:header>
  <div class="row mb-6 gy-6">
    <!-- Basic Layout -->
    <div class="col-xxl">
      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="mb-0">Editing Product</h5>
          <small class="text-body-secondary float-end">Product</small>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route("product.update", $product) }}" enctype="multipart/form-data">
            @csrf
            @method("PATCH")
            <div class="d-flex justify-content-between">
              <div>
                <h5>
                  Currnet Image
                </h5>
              </div>
              <div>
                <img width="100px" class="img" src="{{ asset($product->image) }}" alt="The Image Not Found">
              </div>
              <div></div>
            </div>  
            @csrf
            {{-- ? Name --}}
            <x-form.input type="text" edit="{{ $product->name }}" value="Name" feild="name"></x-form.input>

            {{-- ? Desc --}}
            <x-form.textarea type="text" edit="{{ $product->desc }}" value="Description" feild="desc"></x-form.textarea>
            {{-- ? Price --}}
            <x-form.input type="number" step="any" edit="{{ $product->price }}" value="Price"
              feild="price"></x-form.input>
            <x-form.input type="number" step="any" edit="{{ $product->int_price }}" value="Int Price"
              feild="int_price"></x-form.input>
            {{-- The Edit refrese to the actual value cause of the name that i have created before --}}
            <x-form.input type="number" edit="{{ $product->quantity }}" value="Quantity"
              feild="quantity"></x-form.input>
            <x-form.input type="file" value="Image" feild="image"></x-form.input>
            <div class="row mb-6">
              <label class="col-sm-2 col-form-label" for="basic-default-name">Featured</label>
              <div class="col-sm-10">
                <input class="text-light bg-primary" type="checkbox" {{ $product->featured === "on" ? "checked" : "" }} name="featured">
                @error("featured")
                  <p class="text-danger">{{ $message }}</p>
                @enderror
              </div>
            </div>

            {{-- <x-form.input type="text" value="Tags" feild="tags"></x-form.input> --}}

            <div class="row mb-6">
              <label class="col-sm-2 col-form-label" for="basic-default-name">Select Tags</label>
              <div class="col-sm-10">
                <select class="select-tag select2Primary" name="tags[]" multiple>
                  @foreach ($tags as $tag)
                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                  @endforeach
                </select>
                @error("tags")
                  <p class="text-danger">
                    {{ $message }}
                  </p>
                @enderror
              </div>
            </div>
            {{-- ! Catagory --}}
            <div class="row mb-6">
              <label class="col-sm-2 col-form-label " for="basic-default-name">Select Catagory</label>
              <div class="col-sm-10">
                <div>
                  <select class="bg-black text-white selectCategory" name="catagory_id">
                    <option value="">{{ $product->catagory->name ?? "Select Products" }}</option>
                    @foreach ($catagories as $catagory)
                      <option value="{{ $catagory->id }}">{{ $catagory->name }}</option>
                    @endforeach
                  </select>
                </div>
                @error("catagory_id")
                  <p class="text-danger">
                    {{ $message }}
                  </p>
                @enderror
              </div>
            </div>


            <div class="row justify-content-end">
              <div class="col-sm-10">
                <button type="submit" class="btn btn-primary">Send</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Basic with Icons -->

  </div>
</x-main-layout>
@script
<script type="text/javascript">
  $(".select-tag").select2();
  $(".selectCategory").select2();
</script>

@endScript