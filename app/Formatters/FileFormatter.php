<?php

namespace App\Formatters;

class FileFormatter
{
    /**
     * Format a file size to a human-readable string
     *
     * @param int $size Size in bytes
     * @return string Formatted size with unit
     */
    public static function formatSize($size)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        
        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }
        
        return round($size, 2) . ' ' . $units[$i];
    }
} 