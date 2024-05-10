<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Admin\RentPackageRequest;
use App\Http\Resources\RentPackageResource;
use App\Models\RentPackage;
use App\Services\RentPackageService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Throwable;

class RentPackageController extends BaseApiController
{
    public function __construct(protected RentPackageService $rentPackageSer)
    {
        //
    }

    public function index(Request $rq)
    {
        try {
            $rentPakages = $this->rentPackageSer->getList($rq->get('per_page'));
            return $this->sendResourceResponse(
                RentPackageResource::collection($rentPakages)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }

    public function store(RentPackageRequest $rq)
    {
        try {
            $rentPackage = $this->rentPackageSer->setRequest($rq)->handleCreateRentPackage();
            return $this->sendResourceResponse(
                RentPackageResource::make($rentPackage)
            );
        } catch (Exception $e) {
            Log::error($e);
            return $this->sendError($e->getMessage(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }

    public function active(RentPackage $rentPackage)
    {
        try {
            $rentPackage = $this->rentPackageSer->active($rentPackage);
            return $this->sendResponse([
                'message' => __('alert.update_successful')
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError($e->getMessage());
        }
    }
}
