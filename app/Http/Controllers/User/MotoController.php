<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\BaseApiController;
use App\Http\Resources\MotoDetailResource;
use App\Http\Resources\MotoResource;
use App\Models\Moto;
use App\Services\User\MotoService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class MotoController extends BaseApiController
{
    public function __construct(protected MotoService $motoSer)
    {
        //
    }

    public function index(Request $rq)
    {
        try {
            $motos = $this->motoSer->paginate($rq->all());
            return $this->sendResourceResponse(
                MotoResource::collection($motos)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError(__('alert.params.invalid'), Response::HTTP_BAD_REQUEST);
        }
    }

    public function show(Moto $moto)
    {
        try {
            $moto = $this->motoSer->detail($moto);
            return $this->sendResponse(
                MotoDetailResource::make($moto)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }
}
