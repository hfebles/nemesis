<?php

namespace App\Console\Commands;

use App\Http\Controllers\Conf\ExchangeController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class CronExchange extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'exchange_update:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

        $url = json_decode(Http::get("https://s3.amazonaws.com/dolartoday/data.json"), true);
        (new ExchangeController)->storeCurl([
            'date_exchange' => date('Y-m-d'),
            'amount_exchange' => $url['USD']['sicad2'],
        ]);

        \Log::info($url['USD']['sicad2'] . ' ' . date('Y-m-d'));
    }
}
