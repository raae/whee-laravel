<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CallingAllPapers;

class ImportConferences extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cfps:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     * injecting callingAllPapersAPI
     * injecting TestingCallPapersAPI
     */
    public function handle(CallingAllPapers $cfps)
    {
        dd($cfps->conferences());
    }
}
