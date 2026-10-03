<x-main-layout>
  <x-slot:title>
    Offers
  </x-slot:title>
  <x-slot:header>
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
        <div class="mx-5">

          <button class="btn btn-primary mr-4">
            <a class="text-white" href="{{ route('offer.create') }}">Create A New offer </a>
          </button>
        </div>
      </div>
    </div>
    <div class="table-responsive text-nowrap px-3">
      <table class="table">
        <thead>
          <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Catagory</th>

            <th>Code</th>
            <th>Discount Type</th>
            <th>Discount Value</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Is Active</th>
            <th>Actions</th>
          </tr>
        </thead>

        <tbody class="table-border-bottom-0">
          @foreach ($offers as $offer)
            <tr>
              <td class="small">{{ $offer->id}}</td>
              <td class="small">
                <span class="fw-medium">{{ $offer->name }}</span>
              </td>
              <td class="small">
                @if($offer->catagories)
                  <a href="{{ route("catagory.show", $offer->catagory->id) }}"
                    class="fw-medium">{{ $offer->catagory->name ?? " " }}</a>
                @else
                  <span class="fw-medium">{{ $offer->catagories->name ?? " " }}</span>
                @endif
              </td>
              <td class=" text-green small">{{ $offer->code }}</td>
              <td>
                <span class="fw-medium">{{ $offer->discount_type }}</span>
              </td>
              <td>
                @if($offer->discount_type === "percentage")
                <span class="fw-medium">{{ $offer->discount_value * 100 }}</span>
                @else
                <span class="fw-medium">{{ $offer->discount_value }}</span>
                @endif
              </td>
              <td>
                <span class="fw-medium">{{ $offer->start_date }}</span>
              </td>
              <td>
                <span class="fw-medium">{{ $offer->end_date }}</span>
              </td>
              @if($offer->start_date <= date("Y-m-d") && date("Y-m-d") <= $offer->end_date)
                <td><span class="badge bg-label-primary me-1">Active</span></td>
              @else
                <td><span class="badge bg-label-danger me-1">Not Active</span></td>
              @endif
              <td>
                <div class="dropdown">
                  <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="icon-base ti tabler-dots-vertical"></i>
                  </button>
                  <div class="dropdown-menu">
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