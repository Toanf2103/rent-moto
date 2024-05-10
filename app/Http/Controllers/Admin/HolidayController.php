<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseApiController;
use App\Http\Requests\Admin\HolidayRequest;
use App\Http\Requests\Admin\HolidaySearchRequest;
use App\Http\Requests\Admin\HolidayUpdateRequest;
use App\Http\Resources\HolidayResource;
use App\Models\Holiday;
use App\Services\Admin\HolidayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class HolidayController extends BaseApiController
{
    public function __construct(protected HolidayService $holidaySer)
    {
        //
    }

    public function index(HolidaySearchRequest $rq)
    {
        try {
            $holidays = $this->holidaySer->paginate($rq->all());
            return $this->sendResourceResponse(
                HolidayResource::collection($holidays)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function store(HolidayRequest $rq)
    {
        try {
            $holiday = $this->holidaySer->setRequest($rq)->create();
            return $this->sendResponse(
                HolidayResource::make($holiday)
            );
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function update(Holiday $holiday, HolidayUpdateRequest $rq)
    {
        try {
            $holiday = $this->holidaySer->setRequest($rq)->update($holiday);
            return $this->sendResponse([
                'message' => __('alert.update_successful')
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }

    public function destroy(Holiday $holiday, HolidayUpdateRequest $rq)
    {
        try {
            $holiday = $this->holidaySer->setRequest($rq)->destroy($holiday);
            return $this->sendResponse([
                'message' => __('alert.delete_successful')
            ]);
        } catch (Throwable $e) {
            Log::error($e);
            return $this->sendError();
        }
    }
}
