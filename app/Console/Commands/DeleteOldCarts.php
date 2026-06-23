<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\{Description, Signature};
use Illuminate\Console\Command;

use App\Models\Cart;

class DeleteOldCarts extends Command
{
    /**
     * Execute the console command.
     */
    protected $signature = "app:cart:delete-old";
    protected $description = 'Delete carts older than 1 day';

    public function handle()
    {
        Cart::where("created_at", "<=" ,now()->subDay())->delete();
        $this->info('Old carts deleted.');
    }
}
