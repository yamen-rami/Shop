@guest
  
<x-auth-layout>
  
  <div class="card">
    <h5 class="card-header text-center">Login</h5>
    <div class="card-body">
      <div class="d-flex align-items-center justify-content-center h-px-500">
        <form method="Post" action="{{ route("login") }}" class="w-px-400 border rounded p-3 p-md-5">
          @csrf
          <h3 class="mb-6">Sign In</h3>
          <div class="mb-6">
            <label class="form-label" for="form-alignment-username">Email</label>
            <input type="email" name="email" value="{{ old("email") }}" id="form-alignment-username"
              class="form-control" placeholder="johndoe@email.com" />
            @error('email')
              <p class="text-danger">{{ $message }}</p>
            @enderror
          </div>

          <div class="mb-6 form-password-toggle">
            <label class="form-label" for="form-alignment-password">Password</label>
            <div class="input-group input-group-merge">
              <input type="password" id="form-alignment-password" name="password" class="form-control"
                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                aria-describedby="form-alignment-password2" />
              <span class="input-group-text cursor-pointer" id="form-alignment-password2"><i
                  class="icon-base ti tabler-eye-off"></i></span>
            </div>
            @error('password')
              <p class="text-danger">{{ $message }}</p>
            @enderror
          </div>
          <div class="mb-6">
            <label class="form-check m-0">
              <input type="checkbox" name="remember" class="form-check-input" />
              <span class="form-check-label">Remember me</span>
            </label>
          </div>
          <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary">Login</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</x-auth-layout>
@endguest
