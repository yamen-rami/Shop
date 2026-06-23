
<x-main-layout>
  <x-slot:title>
    Contacts
  </x-slot:title>
  <x-slot:header >
    contact 
  </x-slot:header>
  <div class="card">
    <div class="d-flex justify-between items-center">
      <div>
        <h5 class="card-header">Contact Tabel</h5>
      </div>
      <div>
        <form action="{{ route("contact.index") }}" method="get">
          <div class="d-flex items-center">
            <input placeholder="Name Or Desc Contact" name="search" type="text" class="form-control" />
            <a class="ml-4 btn btn-danger" href="{{ route("contact.index") }}">Clear</a>
          </div>
        </form>
      </div>
      <div class="d-flex mx-4 ">
        <div class="mx-3 mr-2">
          <form action="{{ route("contact.index") }}" method="get">
            <button name="sort" value="{{ $sort ?? "desc" }}" class="btn btn-danger mr-4">
              {{ $sort ?? "desc" }}
            </button>
          </form>
        </div>

      </div>
    </div>
    <div class="table-responsive text-nowrap">
      <table class="table">
        <thead>
          <tr>
            <th>Id</th>
            <th>Title</th>
            <th>Desc</th>
            <th>Email</th>

            <th>User</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody class="table-border-bottom-0">
          @foreach ($contacts as $contact)
            <tr>
              <td>{{ $contact->id}}</td>
              <td>
                <span class="fw-medium">{{ $contact->title }}</span>
              </td>
              
              <td>{{ Str::limit($contact->desc, 40) }}</td>
              <td>{{ $contact->email }}</td>
              <td>
                {{ $contact->user->name }}</td>

            
              <td>
                <div class="dropdown">
                  <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                    <i class="icon-base ti tabler-dots-vertical"></i>
                  </button>
                  <div class="dropdown-menu">
                    {{-- Show contact --}}
                    <a class="dropdown-item" href="{{ route('contact.show', $contact) }}"><i
                        class="icon-base ti tabler-pencil me-1"></i>
                      Show</a>
                    {{-- Edit contact --}}
                    <a class="dropdown-item" href="{{ route('contact.edit', $contact) }}"><i
                        class="icon-base ti tabler-pencil me-1"></i>
                      Edit</a>
                    {{-- Delete contact --}}
                    <form action="{{ route("contact.destroy", $contact->id) }}" method="POST">
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
      {{ $contacts->links()}}
    </div>
  </div>
</x-main-layout>