<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\{Description, Signature};
use Illuminate\Console\Command;

use App\Models\Offer;

#[Signature('app:offers:activate')]
#[Description('Activates pending offers and deactivates expired ones based on today\'s date')]
class ActivateOffers extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = date("Y-m-d");
        Offer::where("is_active", false)->where("start_date", "<=", $today)->where("end_date", '>=', $today)->update(["is_active" => 1]);

        Offer::where("is_active", false)->where("start_date", "<=", $today)
            ->where("end_date", ">=", $today)->update(["is_active" => 1]);
        Offer::where("is_active", true)
            ->where('end_date', '<', $today)
            ->update(["is_active" => 0]);
        $this->info('Offer statuses updated successfully!');
    }
}
