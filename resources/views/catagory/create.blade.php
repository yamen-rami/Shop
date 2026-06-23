@section("title")
  Create Comapny
@endsection
<x-main-layout>
  <div class="row mb-6 gy-6">
    <!-- Basic Layout -->
    <div class="col-xxl">
      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="mb-0">Create catagory</h5>
          <small class="text-body-secondary float-end">catagory</small>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route("catagory.store") }}" >
            @csrf
            {{-- ? Name --}}
            <x-form.input type="text" value="Name" feild="name"></x-form.input>
            <x-form.input type="text" value="Description" feild="desc"></x-form.input>
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