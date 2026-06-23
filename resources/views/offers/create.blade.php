@section("title")
  Create Comapny
@endsection
<x-main-layout>
  <div class="row mb-6 gy-6">
    <!-- Basic Layout -->
    <div class="col-xxl">
      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="mb-0">Create offer</h5>
          <small class="text-body-secondary float-end">offer</small>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route("offer.store") }}" enctype="multipart/form-data">
            @csrf
            {{-- ? Name --}}
            <x-form.input type="text" value="Name" feild="name"></x-form.input>
            {{-- ? Code --}}
            <x-form.input type="text" placeholder="leave empty if you want global offer" value="Code"
              feild="code"></x-form.input>

            {{-- ? discount type --}}
            <x-form.input type="number" value="Discount Value" feild="discount_value"></x-form.input>
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
              
              </div>
            </div>
            <div class="row mb-6">
              <label class="col-sm-2 col-form-label" for="basic-default-name">Discount Type</label>
              <div class="col-sm-10">
                <select class="bg-black text-white rounded" name="discount_type">
                  <option value="">Select Type</option>
                  <option value="percentage">Percentge</option>
                  <option value="fixed_amount">Fixed Amout</option>
                  @error("discount_type")
                    <p class="text-danger">{{ $message }}</p>
                  @enderror
                </select>
              </div>
            </div>
           
            {{-- ? Price --}}
            <x-form.input type="date" value="Start Date" feild="start_date"></x-form.input>
            <x-form.input type="date" value="End Date" feild="end_date"></x-form.input>
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