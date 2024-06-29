<?php

namespace App\Http\Controllers;
use App\Models\Pasien;
use App\Models\RumahSakit;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PasienController extends Controller
{
    //
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Mulai query builder
            $query = Pasien::with('rumahSakit');

            // Filter berdasarkan rentang tanggal jika tersedia
            // Inisialisasi variabel
            $today = Carbon::today();
            $today_f = $today->format('m/d/Y');
            $data_tanggal = "";
            $dateBetween = [];

            // Memproses input tanggal
            if ($request->input('dateBetween_p')) {
                $filter_tanggal = explode(" - ", $request->input('dateBetween_p'));

                if (count($filter_tanggal) === 2) {
                    // Konversi format tanggal dari d/m/Y ke Y-m-d
                    foreach ($filter_tanggal as $date) {
                        $dateObject = Carbon::createFromFormat('d/m/Y', trim($date));
                        if ($dateObject !== false) {
                            $dateBetween[] = $dateObject->format('Y-m-d');
                        }
                    }
                }
            }

            return DataTables::of($query)
                ->addColumn('test', function ($row) {
                    return '-';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<button class="btn btn-warning btn-sm edit" data-id="' . $row->id . '">Ubah</button>';
                    $btn .= ' <button class="btn btn-danger btn-sm delete" data-id="' . $row->id . '">Hapus</button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        // Pengambilan data penjualan terbanyak dan terendah
          $topSelling ="";
          $bottomSelling ="";
        

        return view('pasien.index', compact('topSelling', 'bottomSelling'));
    }

}
