<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pasien;
use App\Models\RumahSakit;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class RumahSakitController extends Controller
{
    //
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // Mulai query builder
            $query = RumahSakit::with('pasien');

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
                    $btn = "<div class='d-flex justify-content-center'>";
                    $btn .= '<button class="btn btn-warning btn-sm mr-2 edit" data-id="' . $row->id . '">Ubah</button>';
                    $btn .= ' <button class="btn btn-danger btn-sm delete" data-id="' . $row->id . '">Hapus</button>';
                    return $btn.'</div>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        // Pengambilan data penjualan terbanyak dan terendah
          $topSelling ="";
          $bottomSelling ="";
        

        return view('rumahsakit.index', compact('topSelling', 'bottomSelling'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string',
            'alamat' => 'required',
            'email' => 'required',
            'telepon' => 'required',
        ]);

        $RumahSakit = RumahSakit::create($validated);

        return response()->json($RumahSakit, 201);
    }
    public function update(Request $request, $id)
    {
        $RumahSakit = RumahSakit::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string',
            'alamat' => 'required',
            'email' => 'required',
            'telepon' => 'required',
        ]);

        $RumahSakit->update($validated);

        return response()->json($RumahSakit);
    }
    public function destroy($id)
    {
        RumahSakit::destroy($id);

        return response()->json(null, 204);
    }
    public function show($id)
    {
        return response()->json(RumahSakit::findOrFail($id));
    }

}
