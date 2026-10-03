@section("title")
  Create Comapny
@endsection
<x-main-layout>
  <div class="row mb-6 gy-6">
    <!-- Basic Layout -->
    <div class="col-xxl" x-data="{ type: 'global' }">
      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="mb-0">Create offer</h5>
          <small class="text-body-secondary float-end">offer</small>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route("offer.update", $offer) }}" enctype="multipart/form-data">
            @csrf
            @method("PATCH")
            {{-- ? Name --}}
            <x-form.input type="text" edit="{{ $offer->name }}" value="Name" feild="name"></x-form.input>
            <x-form.textarea type="text" edit="{{ $offer->name }}" value="Name" feild="name"></x-form.textarea>

            {{-- TYpe --}}
            <div class="row mb-6">
              <label class="col-sm-2 col-form-label" for="basic-default-name">Select Offer Type</label>
              <div class="col-sm-10">
                <div class="mb-3">
                  <select class="form-control text-light bg-inherit" x-model="type" name="type" style="width: 100%;">
                    <option value="global" class=" text-light bg-black">Global</option>
                    <option value="coupon" class=" text-light bg-black">Coupon</option>
                    <option value="categories" class=" text-light bg-black">Catagories</option>
                    <option value="products" class=" text-light bg-black">Products</option>
                  </select>
                </div>
              </div>
            </div>

            {{-- ? Code --}}
            <div x-show="type ==='coupon'">
              <x-form.input type="text" edit="{{ $offer->code }}" value="Code" feild="code"></x-form.input>
            </div>
            <div class="row mb-6" x-show="type==='categories'">
              <label class="col-sm-2 col-form-label" for="basic-default-name">Select Catagory</label>
              <div class="col-sm-10">
                <div class="mb-3">
                  <label for="categories">Offer Categories</label>
                  <select class="form-control select-categories" name="categories[]" multiple="multiple" style="width: 100%;">
                    <option value="">Select Catagories</option>
                    @foreach($categories as $category)
                      <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row mb-6" x-show="type==='products'">
              <label class="col-sm-2 col-form-label" for="basic-default-name">Select Product</label>
              <div class="col-sm-10">
                <div class="mb-3">
                  <label for="categories">Offer For Product</label>
                  <select class="form-control select-products" name="products[]" multiple="multiple" style="width: 100%;">
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                      <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            {{-- ? discount type --}}
            @if($offer->discount_type === "fixed_amount")
              <x-form.input type="number" edit="{{ $offer->discount_value  }}" value="Discount Value"
                feild="discount_value"></x-form.input>
            @else
              <x-form.input type="number" edit="{{ $offer->discount_value * 100 }}" value="Discount Value"
                feild="discount_value"></x-form.input>
            @endif
          
            <div class="row mb-6">
              <label class="col-sm-2 col-form-label" for="basic-default-name">Discount Type</label>
              <div class="col-sm-10">
                <select class="bg-black text-white rounded" name="discount_type">
                  <option value="{{ $offer->discount_type }}">{{ $offer->discount_type }}</option>

                  <option value="percentage">Percentge</option>
                  <option value="fixed_amount">Fixed Amout</option>
                  @error("discount_type")
                    <p class="text-danger">{{ $message }}</p>
                  @enderror
                </select>
              </div>
            </div>
            {{-- ? Price --}}
            <x-form.input type="date" edit="{{ $offer->start_date }}" value="Start Date"
              feild="start_date"></x-form.input>
            <x-form.input type="date" edit="{{ $offer->end_date }}" value="End Date" feild="end_date"></x-form.input>

            <div>

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
  <script >
      $(".select-products").select2();
      $(".select-categories").select2();


  </script>

@endScript