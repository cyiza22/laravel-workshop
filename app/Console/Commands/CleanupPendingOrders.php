<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;

class CleanupPendingOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
 
    protected $signature = 'orders:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete pending orders older than 7 days without items';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cutoff = carbon::now()->subDays(7);
        Order::where('status', 'pending')
            ->where('created_at', '<', $cutoff)
            ->doesntHave('items')
            ->delete();

        this->info('Old pending orders cleaned up successfully.');

    }
}
