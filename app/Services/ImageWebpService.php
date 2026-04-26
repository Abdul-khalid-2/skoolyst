<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use InvalidArgumentException;
use RuntimeException;

class ImageWebpService
{
    public function __construct(
        private ?ImageManager $imageManager = null
    ) {
        // ImageManager is resolved lazily so the container can build this service
        // even when GD/Imagick are missing (if fallback is enabled).
    }

    public static function hasImageProcessor(): bool
    {
        return (extension_loaded('gd') && function_exists('gd_info'))
            || (extension_loaded('imagick') && class_exists(\Imagick::class));
    }

    private function manager(): ImageManager
    {
        if ($this->imageManager !== null) {
            return $this->imageManager;
        }

        if (! self::hasImageProcessor()) {
            throw self::missingDriverException();
        }

        if (extension_loaded('gd') && function_exists('gd_info')) {
            return $this->imageManager = ImageManager::gd();
        }

        if (extension_loaded('imagick') && class_exists(\Imagick::class)) {
            return $this->imageManager = ImageManager::imagick();
        }

        throw self::missingDriverException();
    }

    /**
     * @deprecated Use hasImageProcessor() or manager() — kept for tests / manual resolution
     */
    public static function createImageManager(): ImageManager
    {
        if (extension_loaded('gd') && function_exists('gd_info')) {
            return ImageManager::gd();
        }

        if (extension_loaded('imagick') && class_exists(\Imagick::class)) {
            return ImageManager::imagick();
        }

        throw self::missingDriverException();
    }

    private static function missingDriverException(): RuntimeException
    {
        return new RuntimeException(
            'Image uploads require the PHP GD extension or the Imagick extension. ' .
            'On XAMPP, enable `extension=gd` in php.ini and restart Apache. ' .
            'On Linux, install `php-gd` or `php-imagick` for your PHP version, then restart PHP-FPM. ' .
            'If the error appears only in the browser, the web server may use a different `php.ini` than the CLI. ' .
            'As a temporary measure you can set IMAGE_NO_DRIVER_STORE_ORIGINAL=true in .env. ' .
            'See WEBP_IMAGE_DEPLOY_INSTRUCTIONS.md in the project root.'
        );
    }

    /**
     * Read an uploaded image, encode as WebP, store on the given disk, and return the relative path.
     * The original upload is not persisted (unless no-driver fallback is active).
     */
    public function putUploadedAsWebp(string $diskName, string $directory, UploadedFile $file, int $quality = 80): string
    {
        if (! self::hasImageProcessor()) {
            if (config('app.image.no_driver_store_original', false)) {
                return $this->putUploadedOriginal($diskName, $directory, $file);
            }
            throw self::missingDriverException();
        }

        $directory = trim($directory, '/');
        $path = $directory . '/' . Str::uuid()->toString() . '.webp';

        $source = $file->getRealPath();
        if ($source === false) {
            throw new InvalidArgumentException('Unable to read uploaded file path.');
        }

        $image = $this->manager()->read($source);
        $encoded = $image->toWebp($quality);
        Storage::disk($diskName)->put($path, $encoded->toString());

        return $path;
    }

    private function putUploadedOriginal(string $diskName, string $directory, UploadedFile $file): string
    {
        Log::warning('ImageWebpService: stored original file without WebP (PHP GD/Imagick not loaded). Install ext-gd or set IMAGE_NO_DRIVER_STORE_ORIGINAL=false when fixed.');

        return Storage::disk($diskName)->putFile($directory, $file);
    }

    /**
     * Decode a data-URI image, convert to WebP, store, and return the relative path.
     */
    public function putDataUriAsWebp(string $diskName, string $dataUri, string $directory, int $quality = 80): ?string
    {
        try {
            if (!is_string($dataUri) || !str_starts_with($dataUri, 'data:image/') || !str_contains($dataUri, 'base64,')) {
                return null;
            }

            $imageData = base64_decode(substr($dataUri, strpos($dataUri, ',') + 1) ?: '', true);
            if ($imageData === false || $imageData === '') {
                return null;
            }

            $directory = trim($directory, '/');

            if (! self::hasImageProcessor()) {
                if (config('app.image.no_driver_store_original', false)) {
                    $ext = self::dataUriToExtension($dataUri);
                    $path = $directory . '/' . Str::uuid()->toString() . '.' . $ext;
                    Log::warning('ImageWebpService: stored embedded image as original format (no GD/Imagick).');
                    Storage::disk($diskName)->put($path, $imageData);

                    return $path;
                }

                return null;
            }

            $path = $directory . '/' . Str::uuid()->toString() . '.webp';
            $image = $this->manager()->read($imageData);
            $encoded = $image->toWebp($quality);
            Storage::disk($diskName)->put($path, $encoded->toString());

            return $path;
        } catch (\Throwable) {
            return null;
        }
    }

    private static function dataUriToExtension(string $dataUri): string
    {
        if (preg_match('/^data:image\/([\w+.-]+);base64,/', $dataUri, $m)) {
            $t = strtolower($m[1]);
            $map = [
                'jpeg' => 'jpg',
                'jpg' => 'jpg',
                'png' => 'png',
                'gif' => 'gif',
                'webp' => 'webp',
            ];

            return $map[$t] ?? (str_contains($t, 'png') ? 'png' : 'jpg');
        }

        return 'png';
    }
}
