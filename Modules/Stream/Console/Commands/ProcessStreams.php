<?php

namespace Modules\Stream\Console\Commands;

use App\Services\StreamProcessorService;
use Illuminate\Console\Command;

class ProcessStreams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'streams:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process streams: update statuses and current subjects based on dates';

    protected StreamProcessorService $processor;

    public function __construct(StreamProcessorService $processor)
    {
        parent::__construct();
        $this->processor = $processor;
    }

    public function handle()
    {
        $this->processor->run();
        $this->info('Stream processing completed.');
    }
}
