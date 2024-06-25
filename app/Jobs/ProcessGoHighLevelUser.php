<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\GoHighLevel\Helper\GohighlevelHelper;

class ProcessGoHighLevelUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;
    private $ghl;

    /**
     * Create a new job instance.
     */
    public function __construct($user)
    {
        $this->user = $user;
        $this->ghl = new GohighlevelHelper();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->ghl->createUser($this->user);
    }
}
