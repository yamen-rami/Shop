@section("title")
  Edit company {{ $company->name }}
@endsection
<x-main-layout>
  <x-slot:header>
    Editing {{ $company->name }}
  </x-slot:header>
  <div class="row mb-6 gy-6">
    <!-- Basic Layout -->
    <div class="col-xxl">
      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="mb-0">Edit Company</h5>
          <small class="text-body-secondary float-end">company</small>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route("company.update", $company) }}" enctype="multipart/form-data">
            @csrf
            @method("PATCH")
            <div class="d-flex justify-content-between">
              <div>
                <h5>
                  Currnet Image
                </h5>
              </div>
              <div>
                <img width="100px" class="img" src="{{ asset($company->image) }}" alt="The Image Not Found">
              </div>
              <div></div>
            </div>
            @csrf
            {{-- ? Name --}}
            <x-form.input type="text" edit="{{ $company->name }}" value="Name" feild="name"></x-form.input>
            {{-- ? Desc --}}
            <x-form.textarea type="text" edit="{{ $company->desc }}" value="Description" feild="desc"></x-form.textarea>
            {{-- ? Price --}}
            <x-form.input type="file" value="Image" feild="image"></x-form.input>
            <div class="row mb-6">
              <label class="col-sm-2 col-form-label" for="basic-default-name">Select Products</label>
              <div class="col-sm-10">
                <select class="bg-black text-white rounded" name="product_id">
                  @if($company->products)
                    @foreach ($company->products as $product)
                      <option value="{{ $product->id ?? null }}">{{ $product->name ?? "There Is No Previous Records" }}
                      </option>
                    @endforeach
                  @else
                    <option value="">There Is No Records</option>

                  @endif
                  @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>


            <div class="row justify-content-end">
              <div class="col-sm-10">
                <button type="submit" class="btn btn-primary">Update </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Basic with Icons -->

  </div>
</x-main-layout>