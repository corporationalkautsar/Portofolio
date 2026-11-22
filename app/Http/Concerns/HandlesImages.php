<?php

namespace App\Http\Concerns;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HandlesImages
{
    private function storeImage(?UploadedFile $file, string $directory): ?string
    {
        return $file?->store($directory, 'public');
    }

    private function deleteImage(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Store a new upload and return the path to use; remove the previous file.
     */
    private function replaceImage(?UploadedFile $file, ?string $oldPath, string $directory): ?string
    {
        if (! $file) {
            return $oldPath;
        }

        $newPath = $this->storeImage($file, $directory);
        $this->deleteImage($oldPath);

        return $newPath;
    }
}
