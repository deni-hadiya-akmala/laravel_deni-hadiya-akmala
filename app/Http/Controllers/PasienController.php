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
                ->addColumn('rumah_sakit', function ($row) {
                    return "(". $row->rumahSakit->id .") ". $row->rumahSakit->nama;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<button class="btn btn-warning btn-sm edit" data-id="' . $row->id . '">Ubah</button>';
                    $btn .= ' <button class="btn btn-danger btn-sm delete" data-id="' . $row->id . '">Hapus</button>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $rumah_sakits = RumahSakit::all();
        

        return view('pasien.index', compact('rumah_sakits'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'nama_pasien' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:15',
            'id_rumah_sakit' => 'required',
        ]);

        Pasien::create([
            'nama' => $request->nama_pasien,
            'alamat' => $request->alamat,
            'no_telepon' => $request->no_telepon,
            'rumah_sakit_id' => $request->id_rumah_sakit,
        ]);

        return response()->json(['success' => 'Pasien created successfully.']);
    }

    public function show($id)
    {
        $pasien = Pasien::findOrFail($id);
        return response()->json($pasien);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_pasien' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:15',
            'id_rumah_sakit' => 'required',
        ]);

        $pasien = Pasien::findOrFail($id);
        $pasien->update([
            'nama' => $request->nama_pasien,
            'alamat' => $request->alamat,
            'no_telepon' => $request->no_telepon,
            'rumah_sakit_id' => $request->id_rumah_sakit,
        ]);

        return response()->json(['success' => 'Pasien updated successfully.']);
    }

    public function destroy($id)
    {
        $pasien = Pasien::findOrFail($id);
        $pasien->delete();
        return response()->json(['success' => 'Pasien deleted successfully.']);
    }

}
