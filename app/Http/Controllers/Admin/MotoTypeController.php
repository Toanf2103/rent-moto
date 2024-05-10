<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Admin\MotoTypeRequest;
use App\Http\Requests\Admin\MotoTypeUpdateRequest;
use App\Http\Resources\MotoTypeResource;
use App\Models\MotoType;
use App\Services\Admin\MotoTypeService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;
use Illuminate\Support\Facades\Log;

class MotoTypeController extends BaseApiController
{
    public function __construct(protected MotoTypeService $typeSer)
    {
        //
    }

    public function index(Request $rq)
    {
        try {
            $motoTypes = $this->typeSer->paginate($rq->get('per_page'));
            return $this->sendResourceResponse(
                MotoTypeResource::collection($motoTypes)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }

    public function all()
    {
        try {
            $motoTypes = $this->typeSer->all();
            return $this->sendResponse(
                MotoTypeResource::collection($motoTypes)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }

    public function public()
    {
        try {
            $motoTypes = $this->typeSer->getPublicList();
            return $this->sendResponse(
                MotoTypeResource::collection($motoTypes)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }

    public function store(MotoTypeRequest $rq)
    {
        try {
            $motoType = $this->typeSer->setRequest($rq)->create();
            return $this->sendResponse(
                MotoTypeResource::make($motoType)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }

    public function show(MotoType $motoType)
    {
        return $this->sendResourceResponse(
            MotoTypeResource::make($motoType)
        );
    }

    public function update(MotoTypeUpdateRequest $rq, MotoType $motoType)
    {
        try {
            $this->typeSer->setRequest($rq)->update($motoType->id);
            return $this->sendResponse([
                'message' => __('alert.update_successful')
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }
}
