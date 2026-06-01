
<x-main-layout>
  <x-slot:title>
    Companys
  </x-slot:title>
  <x-slot:header >
    company 
  </x-slot:header>
  <div class="card">
    <div class="d-flex justify-between items-center">
      <div>
        <h5 class="card-header">company Tabel</h5>
      </div>
      <div>
        <form action="{{ route("company.index") }}" method="get">
          <div class="d-flex items-center">
            <input placeholder="Name Or Desc Company" name="search" type="text" class="form-control" />
            <a class="ml-4 btn btn-danger" href="{{ route("company.index") }}">Clear</a>
          </div>
        </form>
      </div>
      <div class="d-flex ">
        <div class="mx-3 mr-2">
          <form action="{{ route("company.index") }}" method="get">
            <button name="sort" value="{{ $sort ?? "desc" }}" class="btn btn-danger mr-4">
              {{ $sort ?? "desc" }}
            </button>
          </form>
        </div>
        <button class="btn btn-primary mr-4">
          <a class="text-white" href="{{ route('company.create') }}">Create A New company </a>
        </button>
      </div>
    </div>
    <div class="table-responsive text-nowrap">
      <table class="table">
        <thead>
          <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Image</th>
            <th>Desc</th>
            <th>Product</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @foreach ($companies as $company)
            <tr>
              <td>{{ $company->id}}</td>
              <td>
                <span class="fw-medium">{{ $company->name }}</span>
              </td>
              <td>
                <ul class="list-unstyled m-0 avatar-group d-flex align-items-center">
                  <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                    class="avatar avatar-xs pull-up" title="Sophia Wilkerson">
                    <img src="{{ asset($company->image) }}" alt="Avatar" class="rounded-circle" />
                  </li>
                </ul>
              </td>
              <td>{{ Str::limit($company->desc, 40) }}</td>
              <td>
                @forelse ($company->products as $product )
                  {{ $product->name }}
                @empty
                <p>There is No Products Related To The Company</p>
                @endforelse
              </td>
              <td>
                <div class="dropdown">
                  <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="icon-base ti tabler-dots-vertical"></i>
                  </button>
                  <div class="dropdown-menu">
                    {{-- Show company --}}
                    <a class="dropdown-item" href="{{ route('company.show', $company) }}"><i
                        class="icon-base ti tabler-pencil me-1"></i>
                      Show</a>
                    {{-- Edit company --}}
                    <a class="dropdown-item" href="{{ route('company.edit', $company) }}"><i
                        class="icon-base ti tabler-pencil me-1"></i>
                      Edit</a>
                    {{-- Delete company --}}
                    <form action="{{ route("company.destroy", $company->id) }}" method="POST">
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
      {{ $companies->links()}}
    </div>
  </div>
</x-main-layout>