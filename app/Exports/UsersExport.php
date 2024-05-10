<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomChunkSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;


class UsersExport implements
    WithColumnFormatting,
    FromCollection,
    WithHeadings,
    WithMapping,
    WithCustomChunkSize
{
    public function __construct(protected $users)
    {
        //
    }

    public function collection()
    {
        return $this->users;
    }

    public function headings(): array
    {
        return [
            'id',
            'email',
            'name',
            'phone_number',
            'dob',
            'role',
            'status',
            'address',
            'created_at'
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
            $row->email,
            $row->name,
            $row->phone_number,
            $row->dob,
            $row->role->name,
            $row->status->name,
            $row->address,
            $row->created_at,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT,
            'D' => NumberFormat::FORMAT_TEXT,
            'H' => NumberFormat::FORMAT_TEXT,
        ];
    }
}
