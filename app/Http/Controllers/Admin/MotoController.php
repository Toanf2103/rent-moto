<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Admin\MotoImportRequest;
use App\Http\Requests\Admin\MotoRequest;
use App\Http\Requests\Admin\MotoUpdateRequest;
use App\Http\Resources\MotoResource;
use App\Models\Moto;
use App\Services\Admin\MotoService;
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

    public function store(MotoRequest $rq)
    {
        try {
            $moto = $this->motoSer->setRequest($rq)->create($rq);
            return $this->sendResponse(
                MotoResource::make($moto)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }

    public function update(MotoUpdateRequest $rq, Moto $moto)
    {
        try {
            $this->motoSer->setRequest($rq)->update($moto);
            return $this->sendResponse([
                'message' => __('alert.update_successful')
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }

    public function show(Moto $moto)
    {
        try {
            $moto = $this->motoSer->showMoto($moto);
            return $this->sendResponse(
                MotoResource::make($moto)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }

    public function export(Request $rq)
    {
        try {
            return $this->sendCsvResponse($this->motoSer->export($rq->all()), 'motos.csv');
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }

    public function import(MotoImportRequest $rq)
    {
        try {
            $this->motoSer->setRequest($rq)->import();
            return $this->sendResponse([
                'message' => __('alert.import_successful')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->sendError(
                $e->getMessage(),
                Response::HTTP_UNPROCESSABLE_ENTITY,
                $e->errors()
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }
}
