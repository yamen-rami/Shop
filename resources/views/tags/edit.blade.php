@section("title")
  Edit tag {{ $tag->name }}
@endsection
<x-main-layout>
  <x-slot:header>
    Editing {{ $tag->name }}
  </x-slot:header>
  <div class="row mb-6 gy-6">
    <!-- Basic Layout -->
    <div class="col-xxl">
      <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
          <h5 class="mb-0">Edit tag</h5>
          <small class="text-body-secondary float-end">tag</small>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route("tag.update", $tag) }}">
            @csrf
            @method("PATCH")
            {{-- ? Name --}}
            <x-form.input type="text" edit="{{ $tag->name }}" value="Name" feild="name"></x-form.input>
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