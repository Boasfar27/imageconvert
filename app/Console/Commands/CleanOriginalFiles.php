<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\ImageConversion;

class CleanOriginalFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-original-files {days=30 : Number of days to keep original files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove original image files after a specified period to save storage space';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->argument('days');
        $this->info("Cleaning original files older than {$days} days...");
        
        $date = Carbon::now()->subDays($days);
        
        // Find conversions older than the specified days
        $conversions = ImageConversion::where('created_at', '<', $date)
            ->whereNotNull('original_path')
            ->get();
            
        $count = 0;
        $errorCount = 0;
        
        foreach ($conversions as $conversion) {
            try {
                // If the original file exists, delete it
                if (Storage::disk('public')->exists($conversion->original_path)) {
                    Storage::disk('public')->delete($conversion->original_path);
                    
                    // Update the record to mark that original file has been removed
                    $conversion->update([
                        'original_path' => null
                    ]);
                    
                    $count++;
                }
            } catch (\Exception $e) {
                $errorCount++;
                Log::error("Failed to delete original file: {$conversion->original_path}", [
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        $this->info("Deleted {$count} original files. Errors: {$errorCount}");
        Log::info("CleanOriginalFiles: Deleted {$count} original files. Errors: {$errorCount}");
        
        return 0;
    }
} 