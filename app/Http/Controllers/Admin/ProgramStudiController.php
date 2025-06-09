<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramStudiModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
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
                    <i class="fa fa-eye"></i> Detail
                </button>
                <button onclick="modalAction(\'' . $editUrl . '\')" class="btn btn-warning btn-sm">
                    <i class="fa fa-edit"></i> Edit
                </button>
                <button onclick="modalAction(\'' . $deleteUrl . '\')" class="btn btn-danger btn-sm">
                    <i class="fa fa-trash"></i> Hapus
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
            'nama' => 'required|min:3|max:100|unique:m_program_studi,nama',
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

    public function import()
    {
        return view('roles.admin.management-prodi.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_prodi' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_prodi');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);
            $insert = [];
            $duplikat = [];

            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris > 1) {
                        $nama = trim($value['A']);
                        if (!empty($nama)) {
                            // Cek apakah nama sudah ada di database
                            $exists = ProgramStudiModel::where('nama', $nama)->exists();
                            if (!$exists) {
                                $insert[] = [
                                    'nama' => $nama,
                                    'created_at' => now(),
                                ];
                            } else {
                                $duplikat[] = $nama;
                            }
                        }
                    }
                }

                if (count($insert) > 0) {
                    ProgramStudiModel::insert($insert);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport: ' . count($insert) . ' data. Duplikat: ' . count($duplikat) . ' data.',
                    'duplikat' => $duplikat
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        }

        return redirect('/');
    }

    public function export_excel(Request $request)
    {
        $programStudi = ProgramStudiModel::select('prodi_id', 'nama')
            ->orderBy('prodi_id', 'asc')
            ->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Program Studi');
        $sheet->getStyle('A1:B1')->getFont()->setBold(true);

        $no = 1;
        $baris = 2;
        foreach ($programStudi as $prodi) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $prodi->nama);
            $no++;
            $baris++;
        }

        foreach (range('A', 'B') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Program Studi');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data_Program_Studi_' . date('Y-m-d_H-i-s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    }

    public function export_pdf()
    {
        $programStudi = ProgramStudiModel::all();

        $data = [
            'programStudi' => $programStudi,
            'title' => 'Laporan Data Program Studi'
        ];

        $pdf = Pdf::loadView('roles.admin.management-prodi.export_pdf', $data);
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();

        return $pdf->stream('Data_Program_Studi_' . date('Y-m-d_H-i-s') . '.pdf');
    }
}