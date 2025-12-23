<?php

namespace App\Console\Commands;

use App\Models\DriverBreadcrumb;
use Illuminate\Console\Command;

class PruneBreadcrumbs extends Command
{
    protected $signature = 'breadcrumbs:prune {--days=90 : Number of days to retain}';
    protected $description = 'Prune old driver breadcrumbs to save database space';

    public function handle(): int
    {
        $days = $this->option('days');
        $cutoffDate = now()->subDays($days);

        $deleted = DriverBreadcrumb::where('captured_at', '<', $cutoffDate)->delete();

        $this->info("Pruned {$deleted} breadcrumbs older than {$days} days.");

        return Command::SUCCESS;
    }
}
