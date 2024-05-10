<?php

namespace App\Imports;

use App\Enums\Moto\MotoStatus;
use App\Repositories\Moto\MotoRepositoryInterface;
use App\Rules\LicensePlateRule;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class MotosImport implements
    ToCollection,
    WithHeadingRow,
    WithValidation,
    WithBatchInserts,
    WithChunkReading
{
    protected $motoRep;
    public function __construct()
    {
        $this->motoRep = app(MotoRepositoryInterface::class);
    }

    public function collection(Collection $rows)
    {
        $createdAt = now();
        $data = $rows->map(function ($row) use ($createdAt) {
            return [
                'name' => $row['name'],
                'license_plate' => $row['license_plate'],
                'status' => $row['status'] ?? null,
                'moto_type_id' => $row['moto_type'],
                'price' => $row['price'],
                'description' => $row['description'],
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];
        })->toArray();
        return $this->motoRep->insert($data);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:5', 'max:100'],
            'license_plate' => [
                'required', 'string',
                'unique:motos,license_plate',
                new LicensePlateRule()
            ],
            'status' => [
                'nullable',
                'in:' . implode(',', MotoStatus::getValues())
            ],
            'moto_type' => ['required', 'exists:moto_types,id'],
            'price' => [
                'required', 'numeric'
            ],
            'description' => ['nullable', 'string'],
        ];
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
