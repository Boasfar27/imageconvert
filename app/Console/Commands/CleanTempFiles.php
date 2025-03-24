<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CleanTempFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-temp-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean temporary files in the storage/app/public/temp directory';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Cleaning temporary files...');
        
        // Check if temp directory exists
        if (!Storage::disk('public')->exists('temp')) {
            $this->info('Temp directory does not exist. Creating...');
            Storage::disk('public')->makeDirectory('temp');
            return;
        }
        
        // Get all files in the temp directory
        $files = Storage::disk('public')->files('temp');
        $count = 0;
        
        // Delete files older than 24 hours
        foreach ($files as $file) {
            $lastModified = Carbon::createFromTimestamp(
                Storage::disk('public')->lastModified($file)
            );
            
            if ($lastModified->diffInHours(now()) >= 24) {
                Storage::disk('public')->delete($file);
                $count++;
            }
        }
        
        $this->info("Deleted {$count} temporary files.");
        Log::info("CleanTempFiles: Deleted {$count} temporary files.");
        
        return 0;
    }
} 