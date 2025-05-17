<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PerusahaanModel;
use App\Models\WilayahModel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class PerusahaanController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Perusahaan Mitra',
            'list' => ['Home', 'Perusahaan Mitra']
        ];

        $page = (object) [
            'title' => 'Daftar perusahaan mitra yang terdaftar dalam sistem'
        ];

        $activeMenu = 'perusahaan-mitra';

        $wilayah = WilayahModel::all();
        return view('roles.admin.management-mitra.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
        ]);
    }

    public function list(Request $request)
    {
        $query = PerusahaanModel::with('lokasi')
            ->select(
                'perusahaan_id',
                'nama',
                'alamat',
                'kontak',
                'wilayah_id',
                'bidang_industri',
                'rating',
                'logo',
                'ringkasan',
                'deskripsi',
                'deskripsi_rating'
            );

        // Filter berdasarkan nama
        if ($request->nama) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }

        // Filter berdasarkan bidang industri
        if ($request->bidang_industri) {
            $query->where('bidang_industri', 'like', '%' . $request->bidang_industri . '%');
        }

        return DataTables::of($query)
            ->addIndexColumn()

            // Menampilkan nama wilayah dari relasi 'lokasi'
            ->addColumn('wilayah', function ($item) {
                return $item->lokasi ? $item->lokasi->nama : '-';
            })

            // Logo sudah dirender dalam bentuk HTML <img>
            ->addColumn('logo', function ($item) {
                return $item->logo
                    ? '<img src="' . asset('storage/logo/' . $item->logo) . '" alt="Logo" height="40">'
                    : '-';
            })

            ->addColumn('aksi', function ($item) {
                $btn = '<button onclick="modalAction(\'' . url('/admin/management-mitra/' . $item->perusahaan_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/admin/management-mitra/' . $item->perusahaan_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/admin/management-mitra/' . $item->perusahaan_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi', 'logo']) 
            ->make(true);
    }
    public function create_ajax()
    {
        $wilayahs = WilayahModel::all();
        return view('roles.admin.management-mitra.create_ajax', compact('wilayahs'));
    }

    public function store_ajax(Request $request)
    {
        if ($request->ajax()) {
            $rules = [
                'nama' => 'required|string|max:150',
                'alamat' => 'required|string',
                'kontak' => 'nullable|string|max:100',
                'wilayah_id' => 'required|exists:m_wilayah,wilayah_id',
                'ringkasan' => 'nullable|string',
                'deskripsi' => 'nullable|string',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
                'bidang_industri' => 'nullable|string|max:100',
                'rating' => 'nullable|numeric|min:0|max:5',
                'deskripsi_rating' => 'nullable|string'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            // Proses upload logo jika ada file
            $logoName = null;
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $logoName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/logo_perusahaan'), $logoName);
            }

            PerusahaanModel::create([
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'kontak' => $request->kontak,
                'wilayah_id' => $request->wilayah_id,
                'ringkasan' => $request->ringkasan,
                'deskripsi' => $request->deskripsi,
                'logo' => $logoName, 
                'bidang_industri' => $request->bidang_industri,
                'rating' => $request->rating,
                'deskripsi_rating' => $request->deskripsi_rating
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data perusahaan berhasil disimpan'
            ]);
        }

        return redirect('/admin/management-mitra');
    }

    public function edit_ajax($perusahaan_id)
    {
        $perusahaan = PerusahaanModel::find($perusahaan_id);
        $wilayah = WilayahModel::all();
        return view('roles.admin.management-mitra.edit_ajax', compact('perusahaan', 'wilayah'));
    }

    public function update_ajax(Request $request, $perusahaan_id)
    {
        if ($request->ajax()) {
            $rules = [
                'nama' => 'required|string|max:150',
                'alamat' => 'required|string',
                'kontak' => 'nullable|string|max:100',
                'wilayah_id' => 'required|exists:m_wilayah,wilayah_id',
                'ringkasan' => 'nullable|string',
                'deskripsi' => 'nullable|string',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'bidang_industri' => 'nullable|string|max:100',
                'rating' => 'nullable|numeric|min:0|max:5',
                'deskripsi_rating' => 'nullable|string'
            ];
    
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors()
                ]);
            }
    
            $perusahaan = PerusahaanModel::find($perusahaan_id);
    
            if (!$perusahaan) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data perusahaan tidak ditemukan',
                    'msgField' => []
                ]);
            }
    
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $logoName = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/logo_perusahaan'), $logoName);
    
                if ($perusahaan->logo && file_exists(public_path('uploads/logo_perusahaan/' . $perusahaan->logo))) {
                    unlink(public_path('uploads/logo_perusahaan/' . $perusahaan->logo));
                }
    
                $perusahaan->logo = $logoName;
            }
    
            $perusahaan->nama = $request->nama;
            $perusahaan->alamat = $request->alamat;
            $perusahaan->kontak = $request->kontak;
            $perusahaan->wilayah_id = $request->wilayah_id;
            $perusahaan->ringkasan = $request->ringkasan;
            $perusahaan->deskripsi = $request->deskripsi;
            $perusahaan->bidang_industri = $request->bidang_industri;
            $perusahaan->rating = $request->rating;
            $perusahaan->deskripsi_rating = $request->deskripsi_rating;
    
            $perusahaan->save();
    
            return response()->json([
                'status' => true,
                'message' => 'Data perusahaan berhasil diperbarui'
            ]);
        }
    
        return redirect('/admin/management-mitra');
    }


    public function confirm_ajax($id)
    {
        $perusahaan = PerusahaanModel::find($id);
        return view('roles.admin.management-mitra.confirm_ajax', compact('perusahaan'));
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax()) {
            $perusahaan = PerusahaanModel::find($id);
            if ($perusahaan) {
                if ($perusahaan->lowonganMagang()->count() > 0) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Perusahaan tidak bisa dihapus karena memiliki data lowongan magang'
                    ]);
                }

                $perusahaan->delete();

                return response()->json([
                    'status' => true,
                    'message' => 'Data perusahaan berhasil dihapus'
                ]);
            }

            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return redirect('/admin/management-mitra');
    }

    public function show_ajax($id)
    {
        $perusahaan = PerusahaanModel::with('lokasi')->find($id);
        return view('roles.admin.management-mitra.show_ajax', compact('perusahaan'));
    }

    //public function import()
    //{
    //    return view('roles.admin.management-perusahaan.import');
    //}

    //public function import_ajax(Request $request)
    //{
    //    if ($request->ajax() || $request->wantsJson()) {
    //        $rules = [
    //            'file_perusahaan' => ['required', 'mimes:xlsx', 'max:2048']
    //        ];

    //        $validator = Validator::make($request->all(), $rules);

    //        if ($validator->fails()) {
    //            return response()->json([
    //                'status' => false,
    //                'message' => 'Validasi Gagal',
    //                'msgField' => $validator->errors()
    //            ]);
    //        }

    //        $file = $request->file('file_perusahaan');
    //        $reader = IOFactory::createReader('Xlsx');
    //        $reader->setReadDataOnly(true);
    //        $spreadsheet = $reader->load($file->getRealPath());
    //        $sheet = $spreadsheet->getActiveSheet();
    //        $data = $sheet->toArray(null, false, true, true);

    //        $insert = [];

    //        if (count($data) > 1) {
    //            foreach ($data as $row => $value) {
    //                if ($row > 1) {
    //                    $insert[] = [
    //                        'nama' => $value['A'],
    //                        'ringkasan' => $value['B'],
    //                        'deskripsi' => $value['C'],
    //                        'alamat' => $value['D'],
    //                        'wilayah_id' => $value['E'],
    //                        'kontak' => $value['F'],
    //                        'bidang_industri' => $value['G'],
    //                        'rating' => $value['H'],
    //                        'deskripsi_rating' => $value['I'],
    //                        'created_at' => now(),
    //                    ];
    //                }
    //            }

    //            if (count($insert) > 0) {
    //                PerusahaanModel::insertOrIgnore($insert);
    //            }

    //            return response()->json([
    //                'status' => true,
    //                'message' => 'Data perusahaan berhasil diimpor'
    //            ]);
    //        } else {
    //            return response()->json([
    //                'status' => false,
    //                'message' => 'Tidak ada data yang diimpor'
    //            ]);
    //        }
    //    }

    //    return redirect('/');
    //}

    // Export data perusahaan ke Excel
    //public function export_excel()
    //{
    //    $perusahaans = PerusahaanModel::with('lokasi')->orderBy('nama')->get();

    //    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    //    $sheet = $spreadsheet->getActiveSheet();

        // Header
    //    $sheet->setCellValue('A1', 'No');
    //    $sheet->setCellValue('B1', 'Nama Perusahaan');
    //    $sheet->setCellValue('C1', 'Ringkasan');
    //    $sheet->setCellValue('D1', 'Deskripsi');
    //    $sheet->setCellValue('E1', 'Alamat');
    //    $sheet->setCellValue('F1', 'Wilayah');
    //    $sheet->setCellValue('G1', 'Kontak');
    //    $sheet->setCellValue('H1', 'Bidang Industri');
    //    $sheet->setCellValue('I1', 'Rating');
    //    $sheet->setCellValue('J1', 'Deskripsi Rating');

    //    $sheet->getStyle('A1:J1')->getFont()->setBold(true);

    //    $no = 1;
    //    $row = 2;
    //    foreach ($perusahaans as $p) {
    //        $sheet->setCellValue('A' . $row, $no++);
    //        $sheet->setCellValue('B' . $row, $p->nama);
    //        $sheet->setCellValue('C' . $row, $p->ringkasan);
    //        $sheet->setCellValue('D' . $row, $p->deskripsi);
    //        $sheet->setCellValue('E' . $row, $p->alamat);
    //        $sheet->setCellValue('F' . $row, $p->lokasi->nama ?? '-');
    //        $sheet->setCellValue('G' . $row, $p->kontak);
    //        $sheet->setCellValue('H' . $row, $p->bidang_industri);
    //        $sheet->setCellValue('I' . $row, $p->rating);
    //        $sheet->setCellValue('J' . $row, $p->deskripsi_rating);
    //        $row++;
    //    }

    //    foreach (range('A', 'J') as $col) {
    //        $sheet->getColumnDimension($col)->setAutoSize(true);
    //    }

    //    $sheet->setTitle('Data Perusahaan');
    //    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    //    $filename = 'Data_Perusahaan_' . date('Ymd_His') . '.xlsx';

    //    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //    header("Content-Disposition: attachment; filename=\"$filename\"");
    //    header('Cache-Control: max-age=0');
    //    $writer->save('php://output');
    //    exit;
    //}

    //public function export_pdf()
    //{
    //    $perusahaans = PerusahaanModel::with('lokasi')->orderBy('nama')->get();

    //    $pdf = PDF::loadView('roles.admin.management-perusahaan.export_pdf', ['perusahaans' => $perusahaans]);
    //    $pdf->setPaper('A4', 'landscape');
    //    $pdf->setOption('isRemoteEnabled', true);

    //    return $pdf->stream('Data_Perusahaan_' . date('Ymd_His') . '.pdf');
    //}
}
