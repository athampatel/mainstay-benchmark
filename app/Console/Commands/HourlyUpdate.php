<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\SchedulerLogController;
use App\Models\SchedulerLog;

class HourlyUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hour:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check and update Invoiced Orders';

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
        $schduler = new SchedulerLogController();
        $schduler->runScheduler();
		    return 0;
		
    }
}
