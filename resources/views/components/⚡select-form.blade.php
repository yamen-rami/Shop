<?php

use Livewire\Component;

new class extends Component {
    //
    public $feild;
};
?>
<div>

    {{-- The whole future lies in uncertainty: live immediately. - Seneca --}}
    <div class="row mb-6">
        <label class="col-sm-2 col-form-label" for="basic-default-name">Select Tags</label>

        <div class="col-sm-10">
            <div>
                <select class="select-tag" style="width:40% ; font-color:black;" name="tags[]" multiple>
                    <option value="">Select Tags</option>
                    @foreach ($feild as $tag)
                        <option class="text-black " value="{{ $tag->id }}">{{ $tag->name }}</option>
                    @endforeach
                </select>
            </div>
            @error("tags")
                <p class="text-danger">
                    {{ $message }}
                </p>
            @enderror
        </div>

    </div>