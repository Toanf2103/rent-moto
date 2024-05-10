<?php

namespace App\Services;

use App\Exports\MotosExport;
use App\Exports\UsersExport;
use App\Imports\MotosImport;
use Maatwebsite\Excel\Facades\Excel;

class ExcelService
{
    public function __construct()
    {
        //
    }

    public function exportMotos($motos)
    {
        return new MotosExport($motos);
    }

    public function exportUsers($users)
    {
        return new UsersExport($users);
    }

    public function importMotos($file)
    {
        return Excel::import(new MotosImport, $file);
    }
}
