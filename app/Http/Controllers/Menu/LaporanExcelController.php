<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanExport;

class LaporanExcelController extends Controller
{
    public function export()
    {
        return Excel::download(new LaporanExport, 'laporan.xlsx');
    }
}