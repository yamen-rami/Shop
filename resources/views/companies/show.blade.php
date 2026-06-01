@section("title")
  Showing Company {{ $company->name }}
@endsection
<x-main-layout>
  <x-slot:header>
    Showing {{ $company->name }}
  </x-slot:header>
  <div class="row mb-12 g-6">
    <div class="col-md">
      <div class="card">
        <div class="row">
          <div class="col-md-4  ">
            <img class="card-img card-img-left"  src="{{ asset($company->image) }}" alt="Card image" />
          </div>
          <div class="col-md-8">
            <div class="card-body">
              <h1 class="card-title fs-4">{{ $company->name }}</h1>
              <p class="card-text"><strong>Description : </strong>{{ $company->desc }}</p>
              <h1>
                @foreach ($company->products as $product )
                  {{ $product->name }}
                @endforeach
              </h1>
              <p class="card-text"><small class="text-body-secondary"> <strong> Created At : </strong>{{ $company->created_at }}</small></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-main-layout>