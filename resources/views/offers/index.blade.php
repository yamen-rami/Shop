
<x-main-layout>
  <x-slot:title>
    Offers
  </x-slot:title>
  <x-slot:header >
    Offers 
  </x-slot:header>
  <div class="card">
    <div class="d-flex justify-between items-center">
      <div>
        <h5 class="card-header">offer Tabel</h5>
      </div>
      <div>
        <form action="{{ route("offer.index") }}" method="get">
          <div class="d-flex items-center">
            <input placeholder="Name Or Desc offer" name="search" type="text" class="form-control" />
            <a class="ml-4 btn btn-danger" href="{{ route("offer.index") }}">Clear</a>
          </div>
        </form>
      </div>
      <div class="d-flex ">
        <div class="mx-3 mr-2">
          <form action="{{ route("offer.index") }}" method="get">
            <button name="sort" value="{{ $sort ?? "desc" }}" class="btn btn-danger mr-4">
              {{ $sort ?? "desc" }}
            </button>
          </form>
        </div>
        <button class="btn btn-primary mr-4">
          <a class="text-white" href="{{ route('offer.create') }}">Create A New offer </a>
        </button>
      </div>
    </div>
    <div class="table-responsive text-nowrap">
      <table class="table">
        <thead>
          <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Desc</th>
            <th>Persentage</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @foreach ($offers as $offer)
            <tr>
              <td>{{ $offer->id}}</td>
              <td>
                <span class="fw-medium">{{ $offer->name }}</span>
              </td>
            
              <td class="text-center">{{ $offer->persantige }}</td>
              <td>
                <div class="dropdown">
                  <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="icon-base ti tabler-dots-vertical"></i>
                  </button>
                  <div class="dropdown-menu">
                    {{-- Show offer --}}
                    <a class="dropdown-item" href="{{ route('offer.show', $offer) }}"><i
                        class="icon-base ti tabler-pencil me-1"></i>
                      Show</a>
                    {{-- Edit offer --}}
                    <a class="dropdown-item" href="{{ route('offer.edit', $offer) }}"><i
                        class="icon-base ti tabler-pencil me-1"></i>
                      Edit</a>
                    {{-- Delete offer --}}
                    <form action="{{ route("offer.destroy", $offer->id) }}" method="POST">
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
      {{ $offers->links()}}
    </div>
  </div>
</x-main-layout>