<?php

namespace App\Services;

use App\Models\Image;
use App\Repositories\Image\ImageRepositoryInterface;

class ImgMotoService
{
    private $path;

    public function __construct(protected ImageRepositoryInterface $imgRep, protected FileService $fileSer)
    {
        $this->path = config('define.path.moto_image');
    }

    public function saveMany($files, $moto)
    {
        $data = [];
        foreach ($files as $file) {
            $image = new Image();
            $newPath = generatePathFile($this->path, $moto->id, $file->getClientOriginalExtension());
            $image->old_name = $file->getClientOriginalName();
            $image->path = $newPath;
            $data[] = $image;
        }
        $this->imgRep->saveMany($moto, $data);
        foreach ($files as $index => $file) {
            $this->fileSer->save($data[$index]->path, $file);
        }
        return;
    }

    public function insert($filenames, $motoId)
    {
        $data = collect($filenames)->map(function ($filename) use ($motoId) {
            return collect([
                'path' => $filename,
                'moto_id' => $motoId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        })->toArray();
        return $this->imgRep->insert($data);
    }

    public function delete($id)
    {
        $paths = $this->imgRep->find($id)->pluck('path')->toArray();
        $this->imgRep->delete($id);
        $this->fileSer->setPath($this->path)->delete($paths);
        return $this->imgRep->delete($id);
    }
}
