@props(['name', 'label', 'type' => 'text', 'value' => null, 'errorBag' => 'default'])

@php($messages = $errors->getBag($errorBag)->get($name))

<span class="ec-login-wrap">
    <label for="{{ $name }}">{{ $label }}*</label>
    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        @if ($type !== 'password') value="{{ $value }}" @endif
        aria-invalid="{{ $messages ? 'true' : 'false' }}"
        @if ($messages) aria-describedby="{{ $name }}-error" @endif
        {{ $attributes->merge(['required' => true]) }}
    >
    @if ($messages)
        <span id="{{ $name }}-error" class="ec-auth-error" role="alert">
            @foreach ($messages as $message)
                <span class="d-block">{{ $message }}</span>
            @endforeach
        </span>
    @endif
</span>
