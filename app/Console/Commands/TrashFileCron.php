<?php

namespace App\Console\Commands;

use App\Services\FileService;
use Illuminate\Console\Command;

class TrashFileCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:trash-file-cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct(protected FileService $fileSer)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->fileSer->deleteExpiredImages();
    }
}
