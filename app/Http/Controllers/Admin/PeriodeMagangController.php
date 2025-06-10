<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeriodeMagangModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;

class PeriodeMagangController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Periode Magang',
            'list' => ['Home', 'Periode Magang']
        ];

        $page = (object) [
            'title' => 'Daftar periode magang yang terdaftar dalam sistem'
        ];

        $activeMenu = 'Manajemen Periode Magang';

        return view('roles.admin.management-periode-magang.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $periodes = PeriodeMagangModel::select('periode_id', 'nama', 'tanggal_mulai', 'tanggal_selesai');

        // Filter berdasarkan nama jika ada
        if ($request->nama) {
            $periodes->where('nama', 'like', '%' . $request->nama . '%');
        }

        return DataTables::of($periodes)
            ->addIndexColumn()
            ->addColumn('aksi', function ($periode) {
                $showUrl = url('/admin/management-periode-magang/' . $periode->periode_id . '/show_ajax');
                $editUrl = url('/admin/management-periode-magang/' . $periode->periode_id . '/edit_ajax');
                $deleteUrl = url('/admin/management-periode-magang/' . $periode->periode_id . '/delete_ajax');

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
        return view('roles.admin.management-periode-magang.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:100',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'msgField' => $validator->errors()
            ]);
        }

        try {
            $periode = PeriodeMagangModel::create([
                'nama' => $request->nama,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai
            ]);
            
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil ditambahkan',
                'id' => $periode->periode_id,
                'nama' => $periode->nama
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menambah data: ' . $e->getMessage()
            ]);
        }
    }

    public function edit_ajax($periode_id)
    {
        $periode = PeriodeMagangModel::find($periode_id);
        return view('roles.admin.management-periode-magang.edit_ajax', compact('periode'));
    }

    public function update_ajax(Request $request, $periode_id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:100',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'msgField' => $validator->errors()
            ]);
        }

        $periode = PeriodeMagangModel::find($periode_id);
        if (!$periode) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $periode->nama = $request->nama;
        $periode->tanggal_mulai = $request->tanggal_mulai;
        $periode->tanggal_selesai = $request->tanggal_selesai;
        $periode->save();

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diupdate',
            'id' => $periode->periode_id,
            'nama' => $periode->nama
        ]);
    }

    public function confirm_ajax($periode_id)
    {
        $periode = PeriodeMagangModel::find($periode_id);
        return view('roles.admin.management-periode-magang.confirm_ajax', compact('periode'));
    }

    public function delete_ajax($periode_id)
    {
        $periode = PeriodeMagangModel::find($periode_id);
        if ($periode) {
            // Check if periode has related records
            if ($periode->lowonganMagang()->count() > 0 ) {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak dapat menghapus periode karena memiliki data terkait'
                ]);
            }
            
            $periode->delete();
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        }
        
        return response()->json([
            'status' => false,
            'message' => 'Data tidak ditemukan'
        ]);
    }

    public function show_ajax($periode_id)
    {
        $periode = PeriodeMagangModel::find($periode_id);
        return view('roles.admin.management-periode-magang.show_ajax', compact('periode'));
    }

    
    public function import()
    {
        return view('roles.admin.management-periode-magang.import');  
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_periode' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_periode');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);
            $insert = [];

            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris > 1) {
                        $insert[] = [
                            'nama' => $value['A'],
                            'tanggal_mulai' => $value['B'],
                            'tanggal_selesai' => $value['C'],
                            'created_at' => now(),
                        ];
                    }
                }

                if (count($insert) > 0) {
                    PeriodeMagangModel::insertOrIgnore($insert);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport'
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

    public function export_excel()
    {
        $periodes = PeriodeMagangModel::all();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Periode');
        $sheet->setCellValue('C1', 'Tanggal Mulai');
        $sheet->setCellValue('D1', 'Tanggal Selesai');
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);

        $no = 1;
        $baris = 2;

        foreach ($periodes as $periode) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $periode->nama);
            $sheet->setCellValue('C' . $baris, $periode->tanggal_mulai);
            $sheet->setCellValue('D' . $baris, $periode->tanggal_selesai);
            $baris++;
            $no++;
        }

        foreach (range('A', 'D') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Periode Magang');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Periode Magang ' . date('Y-m-d H:i:s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
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
        $periodes = PeriodeMagangModel::all();

        $pdf = Pdf::loadView('roles.admin.management-periode-magang.export_pdf', ['periodes' => $periodes]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->render();

        return $pdf->stream('Data Periode Magang ' . date('Y-m-d H:i:s') . '.pdf');
    }
    
}