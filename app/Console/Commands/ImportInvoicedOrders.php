<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\InvoicedOrdersController;
class ImportInvoicedOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'twice:ImportInvoiceOrders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Invoice Orders twice per hour';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $invoice = new InvoicedOrdersController();
        $invoice->getInvoiceOrders();
        return 0;
    }
}
