<?php

namespace App\Console\Commands;

use App\Models\Tool;
use Illuminate\Console\Command;

class ToolStatusCounts extends Command
{
    protected $signature = 'tools:status-counts';

    protected $description = 'Outputs count of tools by status (Available, Checked Out, Maintenance).';

    public function handle(): int
    {
        // TODO: implement real counts from tools.status
        $this->line('Available: 0');
        $this->line('Checked Out: 0');
        $this->line('Maintenance: 0');

        return self::SUCCESS;
    }
}
