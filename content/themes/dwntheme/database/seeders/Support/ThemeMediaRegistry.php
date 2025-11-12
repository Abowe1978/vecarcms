<?php

namespace Themes\DwnTheme\Database\Seeders\Support;

use App\Models\Media;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ThemeMediaRegistry
{
    protected const SOURCE_PREFIX = 'dwntheme:';

    public static function ensure(string $key, array $config): ?Media
    {
        $sourceKey = self::SOURCE_PREFIX . $key;

        $existing = Media::where('source', $sourceKey)->first();
        if ($existing) {
            return $existing;
        }

        $contents = null;
        $mimeType = 'image/jpeg';

        if (!empty($config['source_url'])) {
            try {
                $response = Http::timeout(10)->get($config['source_url']);
                if ($response->successful()) {
                    $contents = $response->body();
                    $mimeType = $response->header('Content-Type') ?: $mimeType;
                }
            } catch (\Throwable $th) {
                // Ignore download errors and fall back to asset if available.
            }
        }

        if (!$contents && !empty($config['asset']) && File::exists($config['asset'])) {
            $contents = File::get($config['asset']);
            $mimeType = File::mimeType($config['asset']) ?: $mimeType;
        }

        if (!$contents) {
            return null;
        }

        $extension = $config['extension'] ?? self::guessExtension($mimeType);
        if (!$extension) {
            $extension = pathinfo($config['file_name'] ?? '', PATHINFO_EXTENSION) ?: 'jpg';
        }

        $fileName = $config['file_name'] ?? (Str::slug($key) . '.' . $extension);
        $directory = trim($config['directory'] ?? 'uploads/dwntheme', '/');
        $path = $directory . '/' . $fileName;

        Storage::disk('public')->put($path, $contents);

        return Media::create([
            'name' => $config['name'] ?? Str::title(str_replace('-', ' ', $key)),
            'file_name' => $fileName,
            'mime_type' => $mimeType,
            'path' => $path,
            'disk' => 'public',
            'size' => strlen($contents),
            'user_id' => $config['user_id'] ?? 1,
            'source' => $sourceKey,
        ]);
    }

    public static function getUrl(string $key, ?string $fallback = null): ?string
    {
        $media = Media::where('source', self::SOURCE_PREFIX . $key)->first();

        return $media?->url ?? $fallback;
    }

    protected static function guessExtension(string $mimeType): ?string
    {
        return match ($mimeType) {
            'image/jpeg', 'image/jpg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            default => null,
        };
    }
}


