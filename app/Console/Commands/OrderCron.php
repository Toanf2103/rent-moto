<?php

namespace App\Console\Commands;

use App\Services\Admin\OrderService;
use Illuminate\Console\Command;

class OrderCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:order-cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct(protected OrderService $orderSer)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->orderSer->cancelExpiredOrders();
    }
}
