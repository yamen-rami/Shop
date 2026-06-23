@section("title")
  Create Product
@endsection
<x-main-layout>
  <div class="row mb-6 gy-6">
    <!-- Basic Layout -->
    <div class="col-xxl">
      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="mb-0">Create Product</h5>
          <small class="text-body-secondary float-end">Product</small>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route("product.store") }}" enctype="multipart/form-data">
            @csrf
            {{-- ? Name --}}
            <x-form.input type="text" value="Name" feild="name"></x-form.input>
            {{-- ? Desc --}}
            <x-form.textarea type="text" value="Description" feild="desc"></x-form.textarea>
            {{-- ? Price --}}
            <x-form.input type="number" step="any" value="Price" feild="price"></x-form.input>
            <x-form.input type="number" step="any" value="Int Price" feild="int_price"></x-form.input>

            <x-form.input type="number" value="Quantity" feild="quantity"></x-form.input>

            <x-form.input type="file" value="Image" feild="image"></x-form.input>
            <div class="row mb-6">
              <label class="col-sm-2 col-form-label" for="basic-default-name">Select Tags</label>

              <div class="col-sm-10">
                <div>

                  <select class="bg-black text-white" name="tags[]" multiple>
                    <option value="">Select Tags</option>
                    @foreach ($tags as $tag)
                      <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                    @endforeach
                  </select>
                </div>
                @error("tags")
                  <p class="text-danger">
                    {{ $message }}
                  </p>
                @enderror
              </div>

            </div>
            <div class="row mb-6">
              <label class="col-sm-2 col-form-label" for="basic-default-name">Select Catagory</label>
              <div class="col-sm-10">
                <div>
                  <select class="bg-black text-white" name="catagory_id">
                    <option value="">Select Catagory </option>
                    @foreach ($catagories as $catagory)
                      <option value="{{ $catagory->id }}">{{ $catagory->name }}</option>
                    @endforeach
                  </select>
                </div>
                @error("catagory")
                  <p class="text-danger">
                    {{ $message }}
                  </p>
                @enderror
              </div>
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