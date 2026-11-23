<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

abstract class Controller
{
    /**
     * Generate a unique slug for the given Eloquent model.
     *
     * @param  class-string<Model>  $modelClass
     */
    protected function generateUniqueSlug(string $modelClass, string $source, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($source) ?: 'entry';
        $slug = $baseSlug;
        $counter = 1;

        while (
            $modelClass::query()
                ->where('slug', $slug)
                ->when($ignoreId !== null, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }

    /**
     * Store an uploaded image on the public disk, replacing any previous file.
     */
    protected function storePublicImage(UploadedFile $file, string $directory, ?string $oldPath = null): string
    {
        $this->deletePublicImage($oldPath);

        return $file->store($directory, 'public');
    }

    /**
     * Remove an image file from the public disk if it exists.
     */
    protected function deletePublicImage(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
