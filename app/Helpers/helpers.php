<?php

if (!function_exists('formatBytes')) {
    /**
     * Format bytes to kb, mb, gb, tb
     *
     * @param int $size
     * @param int $precision
     * @return string
     */
    function formatBytes(int $size, int $precision = 2): string
    {
        if ($size <= 0) {
            return '0 B';
        }

        $base = log($size, 1024);
        $suffixes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }
} 