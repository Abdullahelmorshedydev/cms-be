<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaHandler
{
    public static function store(UploadedFile $file, string $disk = 'public', ?string $path = null, ?string $fileName = null)
    {
        $path = self::normalizePath($path);

        $fileName = $fileName ?: self::randomName($file->getClientOriginalExtension());

        Storage::disk($disk)->putFileAs($path, $file, $fileName);

        return $fileName;
    }

    public static function delete(object $media, string $disk = 'public')
    {
        $path = self::normalizePath($media->media_path);

        $relative = self::buildRelativePath($path, $media->name);

        return Storage::disk($disk)->delete($relative);
    }

    public static function exists(object $media, string $disk = 'public')
    {
        $path = self::normalizePath($media->media_path);

        $relative = self::buildRelativePath($path, $media->name);

        return Storage::disk($disk)->exists($relative);
    }

    /**
     * Generate random filename with extension.
     */
    public static function randomName(?string $extension = null, int $length = 40)
    {
        $extension = $extension ? ltrim($extension, '.') : null;

        return $extension
            ? Str::random($length) . '.' . $extension
            : Str::random($length);
    }

    /**
     * Normalize path: remove leading slash and ensure trailing slash not duplicated.
     */
    private static function normalizePath(?string $path)
    {
        $path = $path ?? '';
        $path = trim($path);

        // Convert "\" to "/" for consistency
        $path = str_replace('\\', '/', $path);

        // Remove leading "/"
        $path = ltrim($path, '/');

        // Remove trailing "/"
        $path = rtrim($path, '/');

        return $path;
    }

    /**
     * Build relative path for Storage disk.
     */
    private static function buildRelativePath(string $path, string $fileName)
    {
        $fileName = ltrim(str_replace('\\', '/', $fileName), '/');

        if ($path === '')
            return $fileName;

        return $path . '/' . $fileName;
    }

    /**
     * Read CSV file (generic).
     */
    public static function readCsv(string $filename, string $delimiter = '|')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = [];

        if (($handle = fopen($filename, 'r')) !== false) {
            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                if (!$header) {
                    $header = $row;
                    continue;
                }

                // Prevent mismatch if row count differs
                if (count($row) !== count($header))
                    continue;

                $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }
}
