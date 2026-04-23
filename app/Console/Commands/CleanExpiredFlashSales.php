<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class CleanExpiredFlashSales extends Command
{
    protected $signature   = 'flash-sale:clean';
    protected $description = 'Disable expired flash sales automatically';

    public function handle(): void
    {
        $count = Product::where('is_flash_sale', true)
            ->where('flash_sale_ends_at', '<', now())
            ->update(['is_flash_sale' => false, 'sale_price' => null]);

        $this->info("Cleaned {$count} expired flash sales.");
    }
}
