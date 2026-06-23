<x-main-layout>
  <x-slot:title>
    Products
  </x-slot:title>
  <x-slot:header>
    Product
  </x-slot:header>
  <div class="card">
    <div class="d-flex justify-between items-center">
      <div>
        <h5 class="card-header">Product Tabel</h5>
      </div>
      <div>
        <form action="{{ route("product.index") }}" method="get">
          <div class="d-flex items-center">
            <input placeholder="Name Or Desc Product" name="search" type="text" class="form-control" />
            <a class="ml-4 btn btn-danger" href="{{ route("product.index") }}">Clear</a>
          </div>
        </form>

      </div>
      <div class="dropdown">
        <button type="button" class="btn  p-0 dropdown-toggle hide-arrow " data-bs-toggle="dropdown">
          Filtering ^
        </button>
        <div class="dropdown-menu">
          {{-- Show Product --}}
          @foreach($catagories as $catagory)
            @if($catagory->products->count() > 0)
              <a class="dropdown-item" href="{{ route('getProducts', $catagory->id) }}">
                {{ $catagory->name }}</a>
            @endif
          @endforeach

        </div>
      </div>
      <div class="d-flex ">
        <div class="mx-3">
          <form action="{{ route("product.index") }}" method="get">
            <button name="sort" value="{{ $sort ?? "desc" }}" class="btn btn-danger mr-4">
              {{ $sort ?? "desc" }}
            </button>
          </form>
        </div>
        <div class="mx-3">
          <button class="btn btn-primary mr-4">
            <a class="text-white" href="{{ route('product.create') }}">Create A New Product </a>
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
            <th>Catagory</th>
            <th>Image</th>
            <th>Desc</th>
            <th>Quantity</th>
            <th>Int Price</th>
            <th>Price</th>
            <th>Tags</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @foreach ($products as $product)
            <tr>
              <td>{{ $product->id}}</td>
              <td>
                <span class="fw-medium">{{ $product->name }}</span>
              </td>
              <td>
                @if($product->catagory)
                  <a href="{{ route("catagory.show", $product->catagory->id) }}"
                    class="fw-medium">{{ $product->catagory->name }}</a>
                @else
                  <span class="fw-medium"></span>
                @endif

              </td>
              <td>
                <ul class="list-unstyled m-0 avatar-group d-flex align-items-center">
                  <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top"
                    class="avatar avatar-xs pull-up" title="Sophia Wilkerson">
                    <img src="{{ asset($product->image) }}" alt="Avatar" class="rounded-circle" />
                  </li>
                </ul>
              </td>
              <td>{{ Str::limit($product->desc, 40) }}</td>
              <td><span class="badge bg-label-primary me-1">{{ $product->quantity }}</span></td>
              <td>{{ $product->int_price }}</td>
              <td>{{ $product->price }}</td>
              <td>
                <div class="d-flex">
                  @foreach ($product->tags as $tag)
                    <a>
                      <button type="submit" class="badge bg-label-primary me-1">{{ $tag->name }}</button>
                    </a>
                  @endforeach
                </div>
              </td>


              <td>
                <div class="dropdown">
                  <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="icon-base ti tabler-dots-vertical"></i>
                  </button>
                  <div class="dropdown-menu">
                    {{-- Show Product --}}
                    <a class="dropdown-item" href="{{ route('product.show', $product) }}"><i
                        class="icon-base ti tabler-pencil me-1"></i>
                      Show</a>
                    {{-- Edit Product --}}
                    <a class="dropdown-item" href="{{ route('product.edit', $product) }}"><i
                        class="icon-base ti tabler-pencil me-1"></i>
                      Edit</a>
                    {{-- Delete Product --}}
                    <form action="{{ route("product.destroy", $product->id) }}" method="POST">
                      @csrf
                      @method("DELETE")
                      <button type="submit" class="dropdown-item"><i class="icon-base ti tabler-trash me-1"></i>
                        Delete</button>
                    </form>
                  </div>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
      {{ $products->links()}}
    </div>
  </div>
</x-main-layout>