@section("title")
  Showing Product {{ $product->name }}
@endsection
<x-main-layout>
  <x-slot:header>
    Showing {{ $product->name }}
  </x-slot:header>
  <div class="row mb-12 g-6">
    <div class="col-md">
      <div class="card">
        <div class="row">
          <div class="col-md-4  ">
            <img class="card-img card-img-left"  src="{{ asset($product->image) }}" alt="Card image" />
          </div>
          <div class="col-md-8">
            <div class="card-body">
              <h1 class="card-title fs-4">{{ $product->name }}</h1>
              <p class="card-text"><strong>Description : </strong>{{ $product->desc }}</p>
              <p class="card-text"><strong>Price : </strong>{{ $product->price }}</p>
              <p class="card-text"><strong>Int Price : </strong>{{ $product->int_price }}</p>
              <p class="card-text"><strong>Quantity : </strong>{{ $product->quantity }}</p>


             
              <p class="card-text"><small class="text-body-secondary"> <strong> Created At : </strong>{{ $product->created_at }}</small></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-main-layout>