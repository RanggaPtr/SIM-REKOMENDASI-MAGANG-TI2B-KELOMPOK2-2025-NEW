<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanMagangModel;
use App\Models\DosenModel;
use App\Models\KompetensiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class PengajuanMagangController extends Controller
{
    public function index()
    {
        return view('roles.admin.pengajuan.index', ['activeMenu' => 'pengajuan']);
    }

    public function list(Request $request)
    {
        $pengajuan = PengajuanMagangModel::with([
            'mahasiswa.user',
            'lowongan.perusahaan',
            'lowongan.periode', // ✅ ambil periode dari lowongan
            'dosen.user'
        ])->select('t_pengajuan_magang.*');

        return DataTables::eloquent($pengajuan)
            ->addColumn('mahasiswa_name', function ($row) {
                return $row->mahasiswa && $row->mahasiswa->user
                    ? $row->mahasiswa->user->nama
                    : '-';
            })
            ->addColumn('perusahaan_name', function ($row) {
                return $row->lowongan && $row->lowongan->perusahaan
                    ? $row->lowongan->perusahaan->nama
                    : 'Belum ditentukan';
            })
            ->addColumn('lowongan_judul', function ($row) {
                return $row->lowongan
                    ? $row->lowongan->judul
                    : 'Belum ditentukan';
            })
            ->addColumn('dosen_name', function ($row) {
                return $row->dosen && $row->dosen->user
                    ? $row->dosen->user->nama
                    : 'Belum ditentukan';
            })
            ->addColumn('periode_name', function ($row) {
                return $row->lowongan && $row->lowongan->periode
                    ? $row->lowongan->periode->nama
                    : 'Belum ditentukan';
            })
            ->addColumn('status', function ($row) {
                return ucfirst($row->status);
            })
            ->addColumn('action', function ($row) {
                return view('roles.admin.pengajuan.action', compact('row'))->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function show_ajax($id)
    {
        $pengajuan = PengajuanMagangModel::with([
            'mahasiswa.user',
            'lowongan.perusahaan',
            'lowongan.periode',
            'dosen.user'
        ])->findOrFail($id);

        return view('roles.admin.pengajuan.show_ajax', compact('pengajuan'));
    }

    public function edit_ajax($id)
    {
        $pengajuan = PengajuanMagangModel::with([
            'mahasiswa.user',
            'lowongan.perusahaan',
            'lowongan.kompetensis',
            'lowongan.periode',
            'dosen.user',
            'dosen.kompentesi'
            
        ])->findOrFail($id);

        $dosens = DosenModel::with(['user', 'kompetensi'])->get();
        $kompetensis = KompetensiModel::all();

        return view('roles.admin.pengajuan.edit_ajax', compact('pengajuan', 'dosens', 'kompetensis'));
    }

    public function update_ajax(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diajukan,diterima,ditolak,selesai',
            'dosen_id' => 'nullable|exists:m_dosen,dosen_id',
        ]);

        DB::beginTransaction();
        try {
            $pengajuan = PengajuanMagangModel::findOrFail($id);
            $oldDosenId = $pengajuan->dosen_id;

            $pengajuan->status = $request->status;

            if ($request->dosen_id) {
                $pengajuan->dosen_id = $request->dosen_id;

                $dosen = DosenModel::find($request->dosen_id);
                if ($dosen) {
                    $dosen->jumlah_bimbingan += 1;
                    $dosen->save();
                }

                if ($oldDosenId && $oldDosenId != $request->dosen_id) {
                    $oldDosen = DosenModel::find($oldDosenId);
                    if ($oldDosen && $oldDosen->jumlah_bimbingan > 0) {
                        $oldDosen->jumlah_bimbingan -= 1;
                        $oldDosen->save();
                    }
                }
            } elseif ($request->status === 'selesai' && $pengajuan->dosen_id) {
                $dosen = DosenModel::find($pengajuan->dosen_id);
                if ($dosen && $dosen->jumlah_bimbingan > 0) {
                    $dosen->jumlah_bimbingan -= 1;
                    $dosen->save();
                }
                $pengajuan->dosen_id = null;
            }

            $pengajuan->save();

            if ($request->status === 'diterima') {
                PengajuanMagangModel::where('mahasiswa_id', $pengajuan->mahasiswa_id)
                    ->where('pengajuan_id', '!=', $pengajuan->pengajuan_id)
                    ->whereIn('status', ['diajukan', 'diterima'])
                    ->update(['status' => 'ditolak']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function confirm_ajax($id)
    {
        $pengajuan = PengajuanMagangModel::findOrFail($id);
        return view('roles.admin.pengajuan.confirm_ajax', compact('pengajuan'));
    }

    public function delete_ajax($id)
    {
        DB::beginTransaction();
        try {
            $pengajuan = PengajuanMagangModel::findOrFail($id);

            if ($pengajuan->dosen_id) {
                $dosen = DosenModel::find($pengajuan->dosen_id);
                if ($dosen && $dosen->jumlah_bimbingan > 0) {
                    $dosen->jumlah_bimbingan -= 1;
                    $dosen->save();
                }
            }

            $pengajuan->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function export_excel()
    {
        $pengajuans = PengajuanMagangModel::with([
            'mahasiswa.user',
            'lowongan.perusahaan',
            'lowongan.periode',
            'dosen.user'
        ])->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Mahasiswa');
        $sheet->setCellValue('C1', 'Judul Lowongan');
        $sheet->setCellValue('D1', 'Perusahaan');
        $sheet->setCellValue('E1', 'Periode');
        $sheet->setCellValue('F1', 'Dosen Pembimbing');
        $sheet->setCellValue('G1', 'Status');
        $sheet->setCellValue('H1', 'Tanggal Pengajuan');
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);

        $no = 1;
        $baris = 2;

        foreach ($pengajuans as $pengajuan) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $pengajuan->mahasiswa && $pengajuan->mahasiswa->user ? $pengajuan->mahasiswa->user->nama : '-');
            $sheet->setCellValue('C' . $baris, $pengajuan->lowongan ? $pengajuan->lowongan->judul : 'Belum ditentukan');
            $sheet->setCellValue('D' . $baris, $pengajuan->lowongan && $pengajuan->lowongan->perusahaan ? $pengajuan->lowongan->perusahaan->nama : 'Belum ditentukan');
            $sheet->setCellValue('E' . $baris, $pengajuan->lowongan && $pengajuan->lowongan->periode ? $pengajuan->lowongan->periode->nama : 'Belum ditentukan');
            $sheet->setCellValue('F' . $baris, $pengajuan->dosen && $pengajuan->dosen->user ? $pengajuan->dosen->user->nama : 'Belum ditentukan');
            $sheet->setCellValue('G' . $baris, ucfirst($pengajuan->status));
            $sheet->setCellValue('H' . $baris, $pengajuan->created_at ? $pengajuan->created_at->format('d-m-Y H:i:s') : '-');
            $baris++;
            $no++;
        }

        // Auto-size columns
        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Pengajuan Magang');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Pengajuan Magang ' . date('Y-m-d H:i:s') . '.xlsx';

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
        $pengajuans = PengajuanMagangModel::with([
            'mahasiswa.user',
            'lowongan.perusahaan',
            'lowongan.periode',
            'dosen.user'
        ])->get();

        $pdf = Pdf::loadView('roles.admin.pengajuan.export_pdf', ['pengajuans' => $pengajuans]);
        $pdf->setPaper('a4', 'landscape'); // Landscape karena banyak kolom
        $pdf->setOption('isRemoteEnabled', true);
        $pdf->render();

        return $pdf->stream('Data Pengajuan Magang ' . date('Y-m-d H:i:s') . '.pdf');
    }
}