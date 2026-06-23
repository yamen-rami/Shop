{{-- ! DONE --}}

<x-main-layout>
  <x-slot:title>
    Catagroy
  </x-slot:title>
  <x-slot:header>
    Catagroy
  </x-slot:header>
  <div class="card">
    <div class="d-flex justify-between items-center">
      <div>
        <h5 class="card-header">Catagroy Tabel</h5>
      </div>
      <div>
        <form action="{{ route("catagory.index") }}" method="get">
          <div class="d-flex items-center">
            <input placeholder="Name Or Desc catagory" name="search" type="text" class="form-control" />
            <a class="ml-4 btn btn-danger" href="{{ route("catagory.index") }}">Clear</a>
          </div>
        </form>
      </div>

      <div class="d-flex mx-4 ">
        <div class="mx-3 mr-2">
          <form action="{{ route("catagory.index") }}" method="get">
            <button name="sort" value="{{ $sort ?? "desc" }}" class="btn btn-danger mr-4">
              {{ $sort ?? "desc" }}
            </button>
          </form>
        </div>
        <div class="mx-3">

          <button class="btn btn-primary mr-4">
            <a class="text-white" href="{{ route('catagory.create') }}">Create A New Catagory </a>
          </button>
        </div>

      </div>
    </div>
    <div class="table-responsive text-nowrap">
      <table class="table">
        <thead>
          <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Description</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @foreach ($catagores as $catagory)
            <tr>
              <td>{{ $catagory->id}}</td>
              <td>
                <span class="fw-medium">{{ $catagory->name }}</span>
              </td>
              <td>
                <span class="fw-medium">{{ $catagory->desc }}</span>
              </td>
              <td>
                <div class="dropdown">
                  <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="icon-base ti tabler-dots-vertical"></i>
                  </button>
                  <div class="dropdown-menu">
                    {{-- SHOW Catagory --}}
                    <a class="dropdown-item" href="{{ route('catagory.show', $catagory) }}"><i
                        class="icon-base ti tabler-comma me-1"></i>
                      Show</a>
                    {{-- Edit catagory --}}
                    <a class="dropdown-item" href="{{ route('catagory.edit', $catagory) }}"><i
                        class="icon-base ti tabler-pencil me-1"></i>
                      Edit</a>
                    {{-- Delete catagory --}}
                    <form action="{{ route("catagory.destroy", $catagory->id) }}" method="POST">
                      @csrf
                      @method("DELETE")
                      <button type="submit" class="dropdown-item"><i class="icon-base ti tabler-trash me-1"></i>
                        Delete</button>
                    </form>
                  </div>
                </div>
              </td>
          @endforeach
          </tr>
        </tbody>
      </table>
      {{ $catagores->links()}}
    </div>
  </div>
</x-main-layout>