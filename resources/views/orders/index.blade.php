@extends('layouts.storefront')

@section('title')
Orders
@endsection

@section('header')
Order
@endsection
@section('content')
  <x-home.navbar />
  <main class="container section-space-p">
    @hasSection('header')
      <h1>@yield('header')</h1>
    @endif


  <div class="card">
    <div class="d-flex justify-between items-center">
      <div>
        <h5 class="card-header">Order Tabel</h5>
      </div>
      <div>
        <form action="{{ route("order.index") }}" method="get">
          <div class="d-flex items-center">
            <input placeholder="Name Or Desc Order" name="search" type="text" class="form-control" />
            <a class="ml-4 btn btn-danger" href="{{ route("order.index") }}">Clear</a>
          </div>
        </form>
      </div>
      <div class="d-flex ">
        <div >
          <form action="{{ route("order.index") }}" method="get">
            <button name="sort" value="{{ $sort ?? "desc" }}" class="btn btn-danger mr-4">
              {{ $sort ?? "desc" }}
            </button>
          </form>
        </div>
        <div class="mx-5">

        <button class="btn btn-primary mr-4">
          <a class="text-white" href="{{ route('order.create') }}">Create A New Order </a>
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
            <th>Location</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Product</th>
            <th>User</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @foreach ($orders as $order)
            <tr>
              <td>{{ $order->id}}</td>
              <td>
                <span class="fw-medium">{{ $order->name }}</span>
              </td>

              <td>{{ Str::limit($order->location, 40) }}</td>

              <td>{{ $order->price }}</td>

              <td><span class="badge bg-label-primary me-1">{{ $order->quantity }}</span></td>
              <td>
                <label for=""></label>
                @forelse ($order->products as $product)

                {{ $product->name }}
                @empty
                  <p style="font-size: 13px">There is No Products</p>
                @endforelse
              </td>
               <td>
                @forelse ($order->user as $user)
                  {{ $user->name }}
                @empty
                  <p style="font-size: 14px" >There is No User</p>
                @endforelse
              </td>
              <td>
                <div class="dropdown">
                  <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="icon-base ti tabler-dots-vertical"></i>
                  </button>
                  <div class="dropdown-menu">
                    {{-- Show Order --}}
                    <a class="dropdown-item" href="{{ route('order.show', $order) }}"><i
                        class="icon-base ti tabler-pencil me-1"></i>
                      Show</a>
                    {{-- Edit Order --}}
                    <a class="dropdown-item" href="{{ route('order.edit', $order) }}"><i
                        class="icon-base ti tabler-pencil me-1"></i>
                      Edit</a>
                    {{-- Delete Order --}}
                    <form action="{{ route("order.destroy", $order->id) }}" method="POST">
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
      {{ $orders->links()}}
    </div>
  </div>
  </main>
  <x-footer />
  <x-home.menu />
@endsection

@push('styles')
  <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}">
@endpush
