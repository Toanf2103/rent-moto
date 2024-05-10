<?php

namespace App\Repositories\Image;


use App\Repositories\RepositoryInterface;

interface ImageRepositoryInterface extends RepositoryInterface
{
    /**
     * Save many
     * @param $model
     * @param $data
     * @return mixed
     */
    public function saveMany($model, $data);

    /**
     * Get image paths exprired
     * @return mixed
     */
    public function getExpiredImagePaths();

    /**
     * Delete images exprired by id
     * @param array $ids
     * @return mixed
     */
    public function deleteExpiredImages($ids);
}
