<?php

namespace App\Http\Controllers\Menu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Imports\LaporanImport;
use Maatwebsite\Excel\Facades\Excel;

class ImportExcelController extends Controller
{
    public function import(Request $request) 
    {
        $request->validate([
            'file' => 'required|mimes:csv,xlsx,xls'
        ]);

        Excel::import(new LaporanImport, $request->file('file'));

        return back()->with('success', 'Data Excel berhasil diimport!');
    }
}

?>
