<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PerusahaanModel;
use App\Models\WilayahModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;

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

        return view('roles.admin.management-mitra.index', compact(
            'breadcrumb',
            'page',
            'activeMenu',
            'wilayah'
        ));
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

        // filter nama
        if ($request->nama) {
            $query->where('nama', 'like', "%{$request->nama}%");
        }
        // filter bidang industri
        if ($request->bidang_industri) {
            $query->where('bidang_industri', 'like', "%{$request->bidang_industri}%");
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('wilayah', fn($item) => $item->lokasi->nama ?? '-')
            ->addColumn('aksi', function ($item) {
                $showUrl = url("/admin/management-mitra/{$item->perusahaan_id}/show_ajax");
                $editUrl = url("/admin/management-mitra/{$item->perusahaan_id}/edit_ajax");
                $deleteUrl = url("/admin/management-mitra/{$item->perusahaan_id}/delete_ajax");

                return "
                <button onclick=\"modalAction('{$showUrl}')\" class=\"btn btn-info btn-sm\">
                    <i class=\"fa fa-eye\"></i> Detail
                </button>
                <button onclick=\"modalAction('{$editUrl}')\" class=\"btn btn-warning btn-sm\">
                    <i class=\"fa fa-edit\"></i> Edit
                </button>
                <button onclick=\"modalAction('{$deleteUrl}')\" class=\"btn btn-danger btn-sm\">
                    <i class=\"fa fa-trash\"></i> Hapus
                </button>
            ";
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax()
    {
        $wilayahs = WilayahModel::all();
        return view('roles.admin.management-mitra.create_ajax', compact('wilayahs'));
    }

    public function store_ajax(Request $request)
    {
        if (!$request->ajax()) {
            return redirect('/admin/management-mitra');
        }

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
            'deskripsi_rating' => $request->deskripsi_rating,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data perusahaan berhasil ditambahkan'
        ]);
    }

    public function edit_ajax($perusahaan_id)
    {
        $perusahaan = PerusahaanModel::find($perusahaan_id);
        $wilayah = WilayahModel::all();
        return view('roles.admin.management-mitra.edit_ajax', compact('perusahaan', 'wilayah'));
    }

    public function update_ajax(Request $request, $perusahaan_id)
    {
        if (!$request->ajax()) {
            return redirect('/admin/management-mitra');
        }

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
                'message' => 'Data perusahaan tidak ditemukan'
            ]);
        }

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $logoName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/logo_perusahaan'), $logoName);
            if ($perusahaan->logo && file_exists(public_path("uploads/logo_perusahaan/{$perusahaan->logo}"))) {
                unlink(public_path("uploads/logo_perusahaan/{$perusahaan->logo}"));
            }
            $perusahaan->logo = $logoName;
        }

        $perusahaan->fill($request->only([
            'nama',
            'alamat',
            'kontak',
            'wilayah_id',
            'ringkasan',
            'deskripsi',
            'bidang_industri',
            'rating',
            'deskripsi_rating'
        ]));
        $perusahaan->save();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diupdate',
            'id' => $perusahaan->perusahaan_id,
            'nama' => $perusahaan->nama,
            'ringkasan' => $perusahaan->ringkasan,
            'deskripsi' => $perusahaan->deskripsi,
            'bidang_industri' => $perusahaan->bidang_industri,
            'alamat' => $perusahaan->alamat,
            'wilayah_id' => $perusahaan->wilayah_id,
            'kontak' => $perusahaan->kontak,
            'rating' => $perusahaan->rating,
            'deskripsi_rating' => $perusahaan->deskripsi_rating,
            'logo' => $perusahaan->logo
        ]);
    }

    public function confirm_ajax($id)
    {
        $perusahaan = PerusahaanModel::find($id);
        return view('roles.admin.management-mitra.confirm_ajax', compact('perusahaan'));
    }

    public function delete_ajax(Request $request, $id)
    {
        if (!$request->ajax()) {
            return redirect('/admin/management-mitra');
        }

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
            'message' => 'Data perusahaan tidak ditemukan'
        ]);
    }

    public function show_ajax($id)
    {
        $perusahaan = PerusahaanModel::with('lokasi')->find($id);
        return view('roles.admin.management-mitra.show_ajax', compact('perusahaan'));
    }

    public function import()
    {
        return view('roles.admin.management-mitra.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_perusahaan' => ['required', 'mimes:xlsx', 'max:2048']
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_perusahaan');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);

            $insert = [];

            if (count($data) > 1) {
                foreach ($data as $row => $value) {
                    if ($row > 1) {
                        $insert[] = [
                            'nama' => $value['A'],
                            'ringkasan' => $value['B'],
                            'deskripsi' => $value['C'],
                            'alamat' => $value['D'],
                            'wilayah_id' => $value['E'],
                            'kontak' => $value['F'],
                            'bidang_industri' => $value['G'],
                            'rating' => $value['H'],
                            'deskripsi_rating' => $value['I'],
                            'created_at' => now(),
                        ];
                    }
                }

                if (count($insert) > 0) {
                    PerusahaanModel::insertOrIgnore($insert);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Data perusahaan berhasil diimpor'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimpor'
                ]);
            }
        }

        return redirect('/');
    }

    public function export_excel()
    {
        $perusahaans = PerusahaanModel::select(
            'perusahaan_id',
            'nama',
            'ringkasan',
            'deskripsi',
            'bidang_industri',
            'alamat',
            'wilayah_id',
            'kontak',
            'rating',
            'deskripsi_rating'
        )
            ->orderBy('perusahaan_id', 'asc')  
            ->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Perusahaan');
        $sheet->setCellValue('C1', 'Ringkasan');
        $sheet->setCellValue('D1', 'Deskripsi');
        $sheet->setCellValue('E1', 'Alamat');
        $sheet->setCellValue('F1', 'Wilayah');
        $sheet->setCellValue('G1', 'Kontak');
        $sheet->setCellValue('H1', 'Bidang Industri');
        $sheet->setCellValue('I1', 'Rating');
        $sheet->setCellValue('J1', 'Deskripsi Rating');

        $sheet->getStyle('A1:J1')->getFont()->setBold(true);

        $no = 1;
        $row = 2;
        foreach ($perusahaans as $p) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $p->nama);
            $sheet->setCellValue('C' . $row, $p->ringkasan);
            $sheet->setCellValue('D' . $row, $p->deskripsi);
            $sheet->setCellValue('E' . $row, $p->alamat);
            $sheet->setCellValue('F' . $row, $p->lokasi->nama ?? '-');
            $sheet->setCellValue('G' . $row, $p->kontak);
            $sheet->setCellValue('H' . $row, $p->bidang_industri);
            $sheet->setCellValue('I' . $row, $p->rating);
            $sheet->setCellValue('J' . $row, $p->deskripsi_rating);
            $row++;
        }

        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $sheet->setTitle('Data Perusahaan');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data_Perusahaan_' . date('Ymd_His') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    public function export_pdf()
    {
        $perusahaan = PerusahaanModel::with('lokasi')
            ->orderBy('perusahaan_id', 'asc')
            ->get();

        $pdf = Pdf::loadView('roles.admin.management-mitra.export_pdf', ['perusahaans' => $perusahaan]);
        $pdf->setPaper('A4', 'landscape');
        $pdf->setOption('isRemoteEnabled', true);

        return $pdf->stream('Data_Perusahaan_' . date('Ymd_His') . '.pdf');
    }
}