<?php

namespace App\Services\Admin;

use App\Enums\Moto\MotoStatus;
use App\Events\MotoLockedEvent;
use App\Models\Moto;
use App\Repositories\Moto\MotoRepositoryInterface;
use App\Repositories\Order\OrderRepositoryInterface;
use App\Services\BaseService;
use App\Services\ExcelService;
use App\Services\ImgMotoService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class MotoService extends BaseService
{
    public function __construct(
        protected MotoRepositoryInterface $motoRepo,
        protected ImgMotoService $imgSer,
        protected ExcelService $excelSer,
        protected OrderRepositoryInterface $orderRepo
    ) {
    }

    public function create($rq)
    {
        DB::beginTransaction();
        try {
            $moto = $this->motoRepo->create($this->data);
            $this->imgSer->saveMany($rq->file('images'), $moto);
            DB::commit();
            return $moto;
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(Moto $moto)
    {
        DB::beginTransaction();
        try {
            $this->imgSer->delete($this->data['images_delete_id']);
            $this->imgSer->saveMany($this->data['images'], $moto);
            unset($this->data['images_delete_id']);
            unset($this->data['images']);
            $this->motoRepo->update($moto->id, $this->data);
            if (isset($this->data['status'])) {
                $this->hanldeSendNotifyMotoBlocked($moto);
            }
            DB::commit();
            return $moto;
        } catch (Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function paginate($filters)
    {
        return $this->motoRepo->paginate($filters);
    }

    public function export($filters)
    {
        $motos = $this->motoRepo->getList($filters);
        return $this->excelSer->exportMotos($motos);
    }

    public function import()
    {
        DB::beginTransaction();
        try {
            $this->excelSer->importMotos($this->data['file']);
            DB::commit();
            return;
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            DB::rollBack();
            $failures = $e->failures();
            $errors = [];
            collect($failures)->each(function ($failure) use (&$errors) {
                $errors[$failure->row()]['row'] = $failure->row();
                $errors[$failure->row()]['errors'][] = $failure->errors()[0];
            });
            $errors = Arr::sort($errors, function ($value) {
                return $value['row'];
            });
            throw \Illuminate\Validation\ValidationException::withMessages(["data_errors" => $errors]);
        }
    }

    public function showMoto(Moto $moto)
    {
        return $this->motoRepo->showMoto($moto);
    }

    private function hanldeSendNotifyMotoBlocked(Moto $moto)
    {
        if (!in_array($this->data['status'], MotoStatus::readyRentValues())) {
            $quantityOrderIssue = $this->orderRepo->getQuantityOrderIssue($moto->id);
            broadcast(new MotoLockedEvent($moto, $quantityOrderIssue));
            $user = auth('api')->user();
            Log::channel('slack')->warning("{$user->name} update moto $moto->name and has $quantityOrderIssue order issue");
        }
        return;
    }
}
