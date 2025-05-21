<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramStudiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ProgramStudiController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Program Studi',
            'list' => ['Home', 'Program Studi']
        ];

        $page = (object) [
            'title' => 'Daftar program studi yang terdaftar dalam sistem'
        ];

        $activeMenu = 'program_studi';

        return view('roles.admin.management-prodi.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $programstudi = ProgramStudiModel::select('prodi_id', 'nama');

        // Filter berdasarkan nama jika ada
        if ($request->nama) {
            $programstudi->where('nama', 'like', '%' . $request->nama . '%');
        }

        return DataTables::of($programstudi)
            ->addIndexColumn()
            ->addColumn('aksi', function ($programstudi) {
                $showUrl = url('/admin/management-prodi/' . $programstudi->prodi_id . '/show_ajax');
                $editUrl = url('/admin/management-prodi/' . $programstudi->prodi_id . '/edit_ajax');
                $deleteUrl = url('/admin/management-prodi/' . $programstudi->prodi_id . '/delete_ajax');

                return '
                <button onclick="modalAction(\'' . $showUrl . '\')" class="btn btn-info btn-sm">
                    <i class=\"fa fa-eye\"></i> Detail
                </button>
                <button onclick="modalAction(\'' . $editUrl . '\')" class="btn btn-warning btn-sm">
                    <i class=\"fa fa-edit\"></i> Edit
                </button>
                <button onclick="modalAction(\'' . $deleteUrl . '\')" class="btn btn-danger btn-sm">
                    <i class=\"fa fa-trash\"></i> Hapus
                </button>
            ';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    public function create_ajax()
    {
        return view('roles.admin.management-prodi.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|min:3|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'msgField' => $validator->errors()
            ]);
        }

        try {
            $prodi = ProgramStudiModel::create([
                'nama' => $request->nama,
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil ditambahkan',
                'id' => $prodi->prodi_id,
                'nama' => $prodi->nama
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menambah data: ' . $e->getMessage()
            ]);
        }
    }

    public function edit_ajax($prodi_id)
    {
        $programStudi = ProgramStudiModel::find($prodi_id);
        return view('roles.admin.management-prodi.edit_ajax', compact('programStudi'));
    }

    public function update_ajax(Request $request, $prodi_id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|min:3|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'msgField' => $validator->errors()
            ]);
        }

        $programStudi = ProgramStudiModel::find($prodi_id);
        if (!$programStudi) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $programStudi->nama = $request->nama;
        $programStudi->save();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diupdate',
            'id' => $programStudi->prodi_id,
            'nama' => $programStudi->nama
        ]);
    }

    public function confirm_ajax($prodi_id)
    {
        $programStudi = ProgramStudiModel::find($prodi_id);
        return view('roles.admin.management-prodi.confirm_ajax', compact('programStudi'));
    }

    public function delete_ajax($prodi_id)
    {
        $programStudi = ProgramStudiModel::find($prodi_id);
        if ($programStudi) {
            $programStudi->delete();
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Data Program Studi tidak ditemukan.'
        ]);
    }

    public function show_ajax($prodi_id)
    {
        $programStudi = ProgramStudiModel::find($prodi_id);
        return view('roles.admin.management-prodi.show_ajax', compact('programStudi'));
    }

    //public function import()
    //{
    //   return view('roles.admin.management-prodi.import');  
    //}

    //public function import_ajax(Request $request)
    //{
    //    if ($request->ajax() || $request->wantsJson()) {
    //        // Validasi file yang diupload
    //        $rules = [
    //            'file_prodi' => ['required', 'mimes:xlsx', 'max:1024']
    //        ];

    //        $validator = Validator::make($request->all(), $rules);

    //        if ($validator->fails()) {
    //            return response()->json([
    //                'status' => false,
    //                'message' => 'Validasi Gagal',
    //                'msgField' => $validator->errors()
    //            ]);
    //        }

    //        // Memproses file Excel
    //        $file = $request->file('file_prodi');
    //        $reader = IOFactory::createReader('Xlsx'); // Membaca file Excel
    //        $reader->setReadDataOnly(true); // Hanya membaca data
    //        $spreadsheet = $reader->load($file->getRealPath());
    //        $sheet = $spreadsheet->getActiveSheet();
    //        $data = $sheet->toArray(null, false, true, true);
    //        $insert = [];

    //        // Memasukkan data jika ada lebih dari 1 baris
    //        if (count($data) > 1) {
    //            foreach ($data as $baris => $value) {
    //                if ($baris > 1) { // Mengabaikan header
    //                    $insert[] = [
    //                        'nama' => $value['A'],  // Kolom A untuk nama program studi
    //                        'created_at' => now(),
    //                    ];
    //                }
    //            }

    //            // Menyimpan data ke database
    //            if (count($insert) > 0) {
    //                ProgramStudiModel::insertOrIgnore($insert);
    //            }

    //            return response()->json([
    //                'status' => true,
    //                'message' => 'Data berhasil diimport'
    //            ]);
    //        } else {
    //            return response()->json([
    //                'status' => false,
    //                'message' => 'Tidak ada data yang diimport'
    //            ]);
    //        }
    //    }

    //    return redirect('/');
    //}

    //public function export_excel()
    //{
    //    // Ambil data program studi yang akan diekspor
    //    $programStudi = ProgramStudiModel::all();

    // Membuat spreadsheet baru
    //    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    //    $sheet = $spreadsheet->getActiveSheet();

    // Set header kolom
    //    $sheet->setCellValue('A1', 'No');
    //    $sheet->setCellValue('B1', 'Nama Program Studi');
    //    $sheet->getStyle('A1:B1')->getFont()->setBold(true); // Bold header

    //    $no = 1;
    //    $baris = 2;

    // Mengisi data program studi
    //    foreach ($programStudi as $key => $value) {
    //        $sheet->setCellValue('A' . $baris, $no);
    //        $sheet->setCellValue('B' . $baris, $value->nama);
    //        $baris++;
    //        $no++;
    //    }

    // Set auto size untuk kolom
    //    foreach (range('A', 'B') as $columnID) {
    //        $sheet->getColumnDimension($columnID)->setAutoSize(true);
    //    }

    // Set judul sheet
    //    $sheet->setTitle('Data Program Studi');

    // Membuat writer dan mengunduh file excel
    //   $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    //    $filename = 'Data Program Studi ' . date('Y-m-d H:i:s') . '.xlsx';

    // Header untuk download
    //    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //    header('Content-Disposition: attachment;filename="' . $filename . '"');
    //    header('Cache-Control: max-age=0');
    //    header('Cache-Control: max-age=1');
    //    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    //    header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    //    header('Cache-Control: cache, must-revalidate');
    //    header('Pragma: public');

    // Menyimpan dan mengekspor file
    //    $writer->save('php://output');
    //    exit;
    //}

    //public function export_pdf()
    //{
    // Ambil data program studi
    //    $programStudi = ProgramStudiModel::all();

    //    $pdf = Pdf::loadView('barang.export_pdf', ['programStudi' => $programStudi]);
    //    $pdf->setPaper('a4', 'portrait');
    //    $pdf->setOption('isRemoteEnabled', true);
    //    $pdf->render();

    //    return $pdf->stream('Data Program Studi ' . date('Y-m-d H:i:s') . '.pdf');
    //}
}