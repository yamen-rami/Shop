@section("title")
  Showing Order {{ $order->name }}
@endsection
<x-main-layout>
  <x-slot:header>
    Showing {{ $order->name }}
  </x-slot:header>
  <div class="row mb-12 g-6">
    <div class="col-md">
      <div class="card">
        <div class="row">
          <div class="col-md-8">
            <div class="card-body">
              <h1 class="card-title fs-4"><strong> Name :</strong> {{ $order->name }}</h1>
              <p class="card-text"><strong>Description : </strong>{{ $order->location }}</p>
              <p class="card-text"><strong>Price : </strong>{{ $order->price }}</p>
              <p class="card-text"><strong>Quantity : </strong>{{ $order->quantity }}</p>

              <p>
                <strong>Products : </strong>
                @foreach ($order->products as $product )
                  {{ $product->name }}
                @endforeach
              </p>
              <p>
                <strong>User : </strong>
                @foreach ($order->user as $user )
                  {{ $user->name }}
                @endforeach
              </p>
              <p class="card-text"><small class="text-body-secondary"> <strong> Created At :
                  </strong>{{ $order->created_at }}</small></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-main-layout>