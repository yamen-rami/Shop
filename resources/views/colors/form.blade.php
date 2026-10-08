@extends('admin')
@section('title', $color->exists ? 'Edit Color' : 'Create Color')

@section('content')
    <div class="card">
        <div class="card-header"><h1 class="h5 mb-0">{{ $color->exists ? 'Edit Color' : 'Create Color' }}</h1></div>
        <div class="card-body">
            <form method="POST" action="{{ $color->exists ? route('color.update', $color) : route('color.store') }}">
                @csrf
                @if($color->exists)
                    @method('PATCH')
                @endif
                <x-form.input type="text" value="Color name" feild="name" :edit="$color->name" required maxlength="100" />
                <div class="d-flex gap-3 justify-content-end">
                    <a href="{{ route('color.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save color</button>
                </div>
            </form>
        </div>
    </div>
@endsection
