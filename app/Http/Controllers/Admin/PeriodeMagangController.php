<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeriodeMagangModel;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

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

        $activeMenu = 'periode-magang';

        return view('roles.admin.management-periode-magang.index', [
            'breadcrumb' => $breadcrumb, 
            'page' => $page, 
            'activeMenu' => $activeMenu
        ]);
    }

    // Ambil data periode magang dalam bentuk json untuk datatables 
    public function list(Request $request)
    {
        $periodes = PeriodeMagangModel::select('id', 'nama', 'tanggal_mulai', 'tanggal_selesai');

        return DataTables::of($periodes)
            ->addIndexColumn()
            ->addColumn('aksi', function ($periode) {
                $btn = '<button onclick="modalAction(\'' . url('/admin/management-periode-magang/' . $periode->id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/admin/management-periode-magang/' . $periode->id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/admin/management-periode-magang/' . $periode->id .
                    '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';

                return $btn;
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
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama' => 'required|string|max:100',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            PeriodeMagangModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data periode magang berhasil disimpan'
            ]);
        }

        return redirect('/admin/management-periode-magang');
    }

    public function edit_ajax(string $id)
    {
        $periode = PeriodeMagangModel::find($id);
        return view('roles.admin.management-periode-magang.edit_ajax', ['periode' => $periode]);
    }

    public function update_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama' => 'required|string|max:100',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $periode = PeriodeMagangModel::find($id);
            if ($periode) {
                $periode->update($request->all());

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/admin/management-periode-magang');
    }

    public function confirm_ajax(string $id)
    {
        $periode = PeriodeMagangModel::find($id);
        return view('roles.admin.management-periode-magang.confirm_ajax', ['periode' => $periode]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $periode = PeriodeMagangModel::find($id);
            if ($periode) {
                // Check if periode has related records
                if ($periode->lowonganMagang()->count() > 0 || $periode->pengajuanMagang()->count() > 0) {
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
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/admin/management-periode-magang');
    }

    public function show_ajax(string $id)
    {
        $periode = PeriodeMagangModel::find($id);
        return view('roles.admin.management-periode-magang.show_ajax', ['periode' => $periode]);
    }

   

    // public function import()
    // {
    //     return view('roles.admin.management-periode-magang.import');
    // }

    // public function import_ajax(Request $request)
    // {
    //     if ($request->ajax() || $request->wantsJson()) {
    //         $rules = [
    //             'file_periode' => ['required', 'mimes:xlsx', 'max:1024']
    //         ];

    //         $validator = Validator::make($request->all(), $rules);

    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'status'   => false,
    //                 'message'  => 'Validasi Gagal',
    //                 'msgField' => $validator->errors()
    //             ]);
    //         }

    //         $file = $request->file('file_periode');
    //         $reader = IOFactory::createReader('Xlsx');
    //         $reader->setReadDataOnly(true);
    //         $spreadsheet = $reader->load($file->getRealPath());
    //         $sheet = $spreadsheet->getActiveSheet();
    //         $data = $sheet->toArray(null, false, true, true);
    //         $insert = [];
    //         $errors = [];

    //         if (count($data) > 1) {
    //             foreach ($data as $baris => $value) {
    //                 if ($baris > 1) {
    //                     $insert[] = [
    //                         'nama' => $value['A'],
    //                         'tanggal_mulai' => $value['B'],
    //                         'tanggal_selesai' => $value['C'],
    //                         'created_at' => now(),
    //                     ];
    //                 }
    //             }

    //             if (count($insert) > 0) {
    //                 PeriodeMagangModel::insertOrIgnore($insert);
    //             }

    //             return response()->json([
    //                 'status'  => true,
    //                 'message' => 'Data berhasil diimport'
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'status'  => false,
    //                 'message' => 'Tidak ada data yang diimport'
    //             ]);
    //         }
    //     }

    //     return redirect('/');
    // }

    // public function export_excel()
    // {
    //     $periodes = PeriodeMagangModel::select(
    //         'id',
    //         'nama',
    //         'tanggal_mulai',
    //         'tanggal_selesai'
    //     )->orderBy('tanggal_mulai', 'desc')->get();

    //     $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    //     $sheet = $spreadsheet->getActiveSheet();

    //     $sheet->setCellValue('A1', 'No');
    //     $sheet->setCellValue('B1', 'Nama Periode');
    //     $sheet->setCellValue('C1', 'Tanggal Mulai');
    //     $sheet->setCellValue('D1', 'Tanggal Selesai');

    //     $sheet->getStyle('A1:D1')->getFont()->setBold(true);

    //     $no = 1;
    //     $baris = 2;
    //     foreach ($periodes as $periode) {
    //         $sheet->setCellValue('A' . $baris, $no);
    //         $sheet->setCellValue('B' . $baris, $periode->nama);
    //         $sheet->setCellValue('C' . $baris, $periode->tanggal_mulai);
    //         $sheet->setCellValue('D' . $baris, $periode->tanggal_selesai);
    //         $no++;
    //         $baris++;
    //     }

    //     foreach (range('A', 'D') as $columnID) {
    //         $sheet->getColumnDimension($columnID)->setAutoSize(true);
    //     }

    //     $sheet->setTitle('Data Periode Magang');
    //     $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    //     $filename = 'Data_Periode_Magang_' . date('Y-m-d_H-i-s') . '.xlsx';

    //     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //     header('Content-Disposition: attachment; filename="' . $filename . '"');
    //     header('Cache-Control: max-age=0');
    //     header('Cache-Control: max-age=1');
    //     header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    //     header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
    //     header('Cache-Control: cache, must-revalidate');
    //     header('Pragma: public');

    //     $writer->save('php://output');
    //     exit;
    // }

    // public function export_pdf()
    // {
    //     $periodes = PeriodeMagangModel::select(
    //         'id',
    //         'nama',
    //         'tanggal_mulai',
    //         'tanggal_selesai'
    //     )->orderBy('tanggal_mulai', 'desc')->get();

    //     $pdf = PDF::loadView('roles.admin.management-periode-magang.export_pdf', ['periodes' => $periodes]);
    //     $pdf->setPaper('A4', 'portrait');
    //     $pdf->setOption("isRemoteEnabled", true);
    //     $pdf->render();

    //     return $pdf->stream('Data Periode Magang ' . date('Y-m-d H-i-s') . '.pdf');
    // }
}