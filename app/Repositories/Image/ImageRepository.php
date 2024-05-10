<?php

namespace App\Repositories\Image;

use App\Models\Image;
use App\Repositories\BaseRepository;
use Carbon\Carbon;

class ImageRepository extends BaseRepository implements ImageRepositoryInterface
{
    public function getModel()
    {
        return Image::class;
    }

    public function saveMany($model, $data)
    {
        return $model->images()->saveMany($data);
    }

    public function getExpiredImagePaths()
    {
        return $this->model
            ->onlyTrashed()
            ->where('deleted_at', '<', Carbon::now()->subMonth(Image::MONTH_EXPIRED))
            ->get();
    }

    public function deleteExpiredImages($ids)
    {
        return $this->model
            ->onlyTrashed()
            ->whereIn('id', $ids)
            ->forceDelete();
    }
}
