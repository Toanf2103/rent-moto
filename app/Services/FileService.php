<?php

namespace App\Services;

use App\Repositories\Image\ImageRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class FileService
{
    public function __construct(protected ImageRepositoryInterface $imgRep)
    {
        //
    }

    protected $path = '';

    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    public function save($path, $file)
    {
        return Storage::disk(config('filesystems.default'))->put($path, file_get_contents($file));
    }

    public function delete($paths)
    {
        foreach ($paths as $path) {
            $file = Storage::disk(config('filesystems.default'))->get($path);
            Storage::disk(config('filesystems.trash'))->put($path, $file);
        }
        return Storage::disk(config('filesystems.default'))->delete($paths);
    }

    public function deleteExpiredImages()
    {
        try {
            $images = $this->imgRep->getExpiredImagePaths();
            if ($images->count() > 0) {
                Storage::disk(config('filesystems.trash'))->delete($images->pluck('path')->toArray());
                $this->imgRep->deleteExpiredImages($images->pluck('id'));
                Log::channel('slack')->info("There are {$images->count()} has been deleted by cron job");
            }
        } catch (Throwable $e) {
            Log::error($e);
            Log::channel('slack')->error("Cron job delete images expired error: " . $e->getMessage());
        }
        return;
    }
}
