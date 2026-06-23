<x-main-layout>
  <x-slot:title>
    tags
  </x-slot:title>
  <x-slot:header>
    tag
  </x-slot:header>
  <div class="card">
    <div class="d-flex justify-between items-center">
      <div>
        <h5 class="card-header">tag Tabel</h5>
      </div>
      <div>
        <form action="{{ route("tag.index") }}" method="get">
          <div class="d-flex items-center">
            <input placeholder="Name Or Desc tag" name="search" type="text" class="form-control" />
            <a class="ml-4 btn btn-danger" href="{{ route("tag.index") }}">Clear</a>
          </div>
        </form>
      </div>

      <div class="d-flex mx-4 ">
        <div class="mx-3 mr-2">
          <form action="{{ route("tag.index") }}" method="get">
            <button name="sort" value="{{ $sort ?? "desc" }}" class="btn btn-danger mr-4">
              {{ $sort ?? "desc" }}
            </button>
          </form>
        </div>
        <div class="mx-3">

          <button class="btn btn-primary mr-4">
            <a class="text-white" href="{{ route('tag.create') }}">Create A New Product </a>
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
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @foreach ($tags as $tag)
            <tr>
              <td>{{ $tag->id}}</td>
              <td>
                <span class="fw-medium">{{ $tag->name }}</span>
              </td>



              <td>
                <div class="dropdown">
                  <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="icon-base ti tabler-dots-vertical"></i>
                  </button>
                  <div class="dropdown-menu">

                    {{-- Edit tag --}}
                    <a class="dropdown-item" href="{{ route('tag.edit', $tag) }}"><i
                        class="icon-base ti tabler-pencil me-1"></i>
                      Edit</a>
                    {{-- Delete tag --}}
                    <form action="{{ route("tag.destroy", $tag->id) }}" method="POST">
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
      {{ $tags->links()}}
    </div>
  </div>
</x-main-layout>