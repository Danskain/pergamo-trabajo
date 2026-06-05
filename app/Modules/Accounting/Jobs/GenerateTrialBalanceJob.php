<?php

namespace App\Modules\Accounting\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateTrialBalanceJob implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        // Placeholder job for future accounting reports.
    }
}
