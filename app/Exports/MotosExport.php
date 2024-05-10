<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomChunkSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Illuminate\Support\Str;


class MotosExport implements
    WithColumnFormatting,
    FromCollection,
    WithHeadings,
    WithMapping,
    WithCustomChunkSize
{
    public function __construct(protected $motos)
    {
        //    
    }

    public function collection()
    {
        return $this->motos;
    }

    public function headings(): array
    {
        return [
            'id',
            'name',
            'license_plate',
            'status',
            'price',
            'moto_type',
            'description',
            'images',
        ];
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->name,
            $row->license_plate,
            $row->status->name,
            $row->price,
            $row->motoType->name,
            $row->description,
            Str::beforeLast($row->images->reduce(function ($carry, $item) {
                return $carry . $item->url . ', ';
            }, ''), ', '),
        ];
    }

    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_TEXT,
        ];
    }
}
