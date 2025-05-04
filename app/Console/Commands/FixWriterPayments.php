<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\WriterPayment;
use Illuminate\Console\Command;

class FixWriterPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-writer-payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate writer payments for completed orders that do not have them';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fixing writer payments for completed orders...');

        // Get all completed orders without writer payments
        $orders = Order::where('status', 'completed')
                      ->whereDoesntHave('writerPayment')
                      ->whereNotNull('writer_id')
                      ->get();

        $this->info("Found {$orders->count()} completed orders without writer payments.");

        $count = 0;
        foreach ($orders as $order) {
            // Calculate writer's payment (40% of order price)
            $writerAmount = $order->price * 0.4;

            // Create the writer payment
            WriterPayment::create([
                'writer_id' => $order->writer_id,
                'order_id' => $order->id,
                'amount' => $writerAmount,
                'status' => 'pending',
                'payment_date' => now(),
            ]);

            $count++;
            $this->info("Created payment for Order #{$order->id} - Amount: \${$writerAmount}");
        }

        $this->info("Successfully created {$count} writer payments.");
        
        return Command::SUCCESS;
    }
}
