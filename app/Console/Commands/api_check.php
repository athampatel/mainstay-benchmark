<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\SDEApi;

class api_check extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:sdeApi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the SDE Api connection';

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
        $sdeApi = new SDEApi();
        $data = [
            "limit" => 1,
            "offset" => 1
        ];
        $response = $sdeApi->CheckConnection();
        // if($response) {
        //     \Artisan::call('down');
        // } else {
        //     \Artisan::call('up');
        // }
        return 0;
    }
}
