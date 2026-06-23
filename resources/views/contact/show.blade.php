@section("title")
  Showing Contact {{ $contact->title }}
@endsection
<x-main-layout>
  <x-slot:header>
    Showing {{ $contact->title }}
  </x-slot:header>
  <div class="row mb-12 g-6">
    <div class="col-md">
      <div class="card">
        <div class="row">
         
          <div class="col-md-8">
            <div class="card-body">
              <h1 class="card-title fs-4">Title : {{ $contact->title }}</h1>
              <p class="card-text"><strong>Description : </strong>{{ $contact->desc }}</p>
              <p class="card-text"><strong>Email : </strong>{{ $contact->email }}</p>

              <h1>
                <strong>Coming Form :</strong>
               {{ $contact->user->name }}
              </h1>
              <p class="card-text"><small class="text-body-secondary"> <strong> Sending At : </strong>{{ $contact->created_at }}</small></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-main-layout>