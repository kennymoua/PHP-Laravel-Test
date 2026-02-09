<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tool;

class ToolsStatusCountsCommand extends Command
{
    // This is the CLI command signature that will be used to run this command from the terminal
    protected $signature = 'tools:status-counts';
    
    // This is the description of the command that will be shown when listing available commands
    protected $description = 'Display counts of tools by their status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
    // Count each status
    $available = Tool::where('status', Tool::STATUS_AVAILABLE)->count();
    $checkedOut = Tool::where('status', Tool::STATUS_CHECKED_OUT)->count();
    $maintenance = Tool::where('status', Tool::STATUS_MAINTENANCE)->count();
    
    // Output with exact labels the test expects
    $this->info("Available: {$available}");
    $this->info("Checked Out: {$checkedOut}");  
    $this->info("Maintenance: {$maintenance}");
    
    return 0;
    }
}

 /**
     * Execute the console command.
     * 
     * IMPLEMENTATION APPROACHES:
     * 
     * METHOD 1 (CURRENT): Explicit queries - Clear and beginner-friendly
     * - Makes 3 separate database queries
     * - Easy to read and understand
     * - Good for learning/teaching
     * 
     * METHOD 2: Loop approach - More DRY (Don't Repeat Yourself)
     * - Still makes 3 queries but with less code duplication
     * - Easy to add new statuses
     * - Example:
     *   $statuses = ['Available' => Tool::STATUS_AVAILABLE, ...];
     *   foreach ($statuses as $label => $status) {
     *       $count = Tool::where('status', $status)->count();
     *       $this->info("{$label}: {$count}");
     *   }
     * 
     * METHOD 3: Database grouping - Most efficient (Production approach)
     * - Makes only 1 database query with GROUP BY
     * - Best performance at scale
     * - Example:
     *   $counts = Tool::selectRaw('status, COUNT(*) as count')
     *       ->groupBy('status')
     *       ->pluck('count', 'status');
     * 
     * TRADEOFFS:
     * - Current approach: Prioritizes clarity over performance
     * - Production apps with high traffic should use METHOD 3
     * - For a small inventory system, current approach is fine
     * 
     * "What's the time complexity?"
     * 
     * "Both approaches are O(n) since they scan through all tools. 
     * However, Method 1 makes 3 separate queries, so it's effectively 
     * O(3n) with three times the network overhead. Method 3 uses a single 
     * grouped query, so while it's still O(n), it's 3× faster in practice 
     * due to fewer round-trips and better constant factors. Big O doesn't 
     * capture the query count difference, which is the real bottleneck here."
     */
