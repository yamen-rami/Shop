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
          <h5 class="mb-0">Create Product</h5>
          <small class="text-body-secondary float-end">Product</small>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route("product.update" , $product) }}" enctype="multipart/form-data">
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
            <x-form.input type="number" edit="{{ $product->price }}" value="Price" feild="price"></x-form.input>
            <x-form.input type="number" edit="{{ $product->int_price }}" value="Int Price"
              feild="int_price"></x-form.input>
            {{-- The Edit refrese to the actual value cause of the name that i have created before --}}
            <x-form.input type="number" edit="{{ $product->quantity }}" value="Quantity"
              feild="quantity"></x-form.input>
            <x-form.input type="file" value="Image" feild="image"></x-form.input>



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