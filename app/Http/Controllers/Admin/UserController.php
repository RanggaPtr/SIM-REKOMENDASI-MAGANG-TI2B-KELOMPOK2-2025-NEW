<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UsersModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UserController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list' => ['Home', 'User']
        ];

        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        $activeMenu = 'user';

        return view('roles.admin.management-pengguna.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request)
    {
        $users = UsersModel::select('user_id', 'username', 'nama', 'email', 'role', 'no_telepon');

        // Filter data user berdasarkan role
        if ($request->role) {
            $users->where('role', $request->role);
        }

        return DataTables::of($users)
            ->addIndexColumn()
            ->addColumn('aksi', function ($user) {
                $showUrl = url('/admin/management-pengguna/' . $user->user_id . '/show_ajax');
                $editUrl = url('/admin/management-pengguna/' . $user->user_id . '/edit_ajax');
                $deleteUrl = url('/admin/management-pengguna/' . $user->user_id . '/delete_ajax');

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
        return view('roles.admin.management-pengguna.create_ajax');
    }

    public function store_ajax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:100',
            'username' => 'required|string|min:3|unique:m_users,username',
            'email' => 'required|email|unique:m_users,email',
            'password' => 'required|min:6',
            'role' => 'required|string',
            'no_telepon' => 'nullable|string',
            'alamat' => 'nullable|string',
            'foto_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'msgField' => $validator->errors()
            ]);
        }

        try {
            $data = $request->except('foto_profile');
            $data['password'] = Hash::make($request->password);

            // Handle file upload
            if ($request->hasFile('foto_profile')) {
                $imageName = time() . '.' . $request->foto_profile->extension();
                $request->foto_profile->move(public_path('images/profile'), $imageName);
                $data['foto_profile'] = 'images/profile/' . $imageName;
            }

            $user = UsersModel::create($data);

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil ditambahkan',
                'id' => $user->user_id,
                'username' => $user->username
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menambah data: ' . $e->getMessage()
            ]);
        }
    }

    public function edit_ajax($user_id)
    {
        $user = UsersModel::find($user_id);
        return view('roles.admin.management-pengguna.edit_ajax', compact('user'));
    }

    public function update_ajax(Request $request, $user_id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:100',
            'username' => 'required|string|min:3|unique:m_users,username,' . $user_id . ',user_id',
            'email' => 'required|email|unique:m_users,email,' . $user_id . ',user_id',
            'password' => 'nullable|min:6',
            'role' => 'required|string',
            'no_telepon' => 'nullable|string',
            'alamat' => 'nullable|string',
            'foto_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi gagal',
                'msgField' => $validator->errors()
            ]);
        }

        $user = UsersModel::find($user_id);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        $data = $request->except(['password', 'foto_profile']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Handle file upload
        if ($request->hasFile('foto_profile')) {
            // Delete old image if exists
            if ($user->foto_profile && file_exists(public_path($user->foto_profile))) {
                unlink(public_path($user->foto_profile));
            }

            $imageName = time() . '.' . $request->foto_profile->extension();
            $request->foto_profile->move(public_path('images/profile'), $imageName);
            $data['foto_profile'] = 'images/profile/' . $imageName;
        }

        $user->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diupdate',
            'id' => $user->user_id,
            'username' => $user->username
        ]);
    }

    public function confirm_ajax($user_id)
    {
        $user = UsersModel::find($user_id);
        return view('roles.admin.management-pengguna.confirm_ajax', compact('user'));
    }

    public function delete_ajax($user_id)
    {
        $user = UsersModel::find($user_id);
        if ($user) {
            // Delete profile photo if exists
            if ($user->foto_profile && file_exists(public_path($user->foto_profile))) {
                unlink(public_path($user->foto_profile));
            }

            $user->delete();
            return response()->json([
                'status' => true,
                'message' => 'Data berhasil dihapus'
            ]);
        }
        return response()->json([
            'status' => false,
            'message' => 'Data User tidak ditemukan.'
        ]);
    }

    public function show_ajax($user_id)
    {
        $user = UsersModel::find($user_id);
        return view('roles.admin.management-pengguna.show_ajax', compact('user'));
    }

    public function import()
    {
        return view('roles.admin.management-pengguna.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_user' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status'   => false,
                    'message'  => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_user');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);
            $insert = [];
            $errors = [];

            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris > 1) {
                        $insert[] = [
                            'nama' => $value['A'],
                            'username' => $value['B'],
                            'email' => $value['C'],
                            'password' => bcrypt($value['D']),
                            'role' => $value['E'],
                            'no_telepon' => $value['F'] ?? null,
                            'alamat' => $value['G'] ?? null,
                            'created_at' => now(),
                        ];
                    }
                }

                if (count($insert) > 0) {
                    UsersModel::insertOrIgnore($insert);
                }

                return response()->json([
                    'status'  => true,
                    'message' => 'Data berhasil diimport'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        }

        return redirect('/');
    }

    public function export_excel()
    {
        $users = UsersModel::select(
            'user_id',
            'username',
            'nama',
            'email',
            'role',
            'no_telepon'
        )->orderBy('role')->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Username');
        $sheet->setCellValue('C1', 'Nama');
        $sheet->setCellValue('D1', 'Email');
        $sheet->setCellValue('E1', 'Role');
        $sheet->setCellValue('F1', 'No Telepon');

        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        $no = 1;
        $baris = 2;
        foreach ($users as $user) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $user->username);
            $sheet->setCellValue('C' . $baris, $user->nama);
            $sheet->setCellValue('D' . $baris, $user->email);
            $sheet->setCellValue('E' . $baris, $user->role);
            $sheet->setCellValue('F' . $baris, $user->no_telepon);
            $no++;
            $baris++;
        }

        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data User');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data_User_' . date('Y-m-d_H-i-s') . '.xlsx';

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
        $users = UsersModel::with(['mahasiswa', 'dosen', 'admin'])->get();

        $data = [
            'users' => $users,
            'title' => 'Laporan Data Pengguna'
        ];

        $pdf = Pdf::loadView('roles.admin.management-pengguna.export_pdf', $data);
        $pdf->setPaper('A4', 'portrait');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();

        return $pdf->stream('Data User ' . date('Y-m-d H-i-s') . '.pdf');
    }
}
