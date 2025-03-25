<?php

namespace App\Helpers;

class ViteHelper
{
    /**
     * Get the asset URL from Vite manifest
     *
     * @param string $entry Path to the asset relative to resources directory
     * @return string|null URL to the asset or null if not found
     */
    public static function asset(string $entry): ?string
    {
        // Don't use manifest in local development
        if (app()->environment('local')) {
            return null;
        }

        $manifest = self::getManifest();

        if (!isset($manifest[$entry])) {
            // Fallback untuk manifest non-standard
            if ($entry === 'resources/css/app.css' && file_exists(public_path('build/assets/app-DPbV5EVT.css'))) {
                return asset('build/assets/app-DPbV5EVT.css');
            }
            
            if ($entry === 'resources/js/app.js' && file_exists(public_path('build/assets/app-DQNOlDuK.js'))) {
                return asset('build/assets/app-DQNOlDuK.js');
            }
            
            return null;
        }

        return asset('build/' . $manifest[$entry]['file']);
    }

    /**
     * Get the Vite manifest
     *
     * @return array The manifest array
     */
    private static function getManifest(): array
    {
        $manifestPaths = [
            public_path('build/manifest.json'),
            public_path('build/.vite/manifest.json')
        ];
        
        foreach ($manifestPaths as $path) {
            if (file_exists($path)) {
                return json_decode(file_get_contents($path), true);
            }
        }

        return [];
    }
}
