<?php

use Livewire\Component;
use App\Models\Catagory;
new class extends Component {
    //
    public $categories;
    public function mount()
    {
        $this->categories = Catagory::with("products")->get();
    }
};
?>

<div>
    @foreach($this->categories as $category)
        <ul>
            <li>
                <div class="ec-sidebar-block-item "><img src="{{ asset('assets/images/icons/dress-8.png') }}" class="svg_img"
                        alt="drink" />{{ $category->name }}</div>
                <ul style="display: block;">
                    @foreach($category->products as $product)
                        <li>
                            <div class="ec-sidebar-sub-item"><a
                                    href="{{ route("showProduct", $product->id) }}">{{ $product->name }} <span
                                        title="Available Stock">{{ $product->discount_price }}</span></a>
                            </div>
                        </li>
                    @endforeach

                </ul>
            </li>
        </ul>
    @endforeach

</div>