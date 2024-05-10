<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\BaseApiController;
use App\Http\Resources\MotoTypeResource;
use App\Services\User\MotoTypeService;
use Illuminate\Support\Facades\Log;
use Throwable;

class MotoTypeController extends BaseApiController
{
    public function __construct(protected MotoTypeService $motoTypeService)
    {
    }

    public function index()
    {
        try {
            $motoTypes = $this->motoTypeService->getAll();
            return $this->sendResponse(
                MotoTypeResource::collection($motoTypes)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }
}
