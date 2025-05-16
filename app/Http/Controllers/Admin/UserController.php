<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UsersModel;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Hash;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

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

    // Ambil data user dalam bentuk json untuk datatables 
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
                $btn = '<button onclick="modalAction(\'' . url('/admin/management-pengguna/' . $user->user_id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/admin/management-pengguna/' . $user->user_id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/admin/management-pengguna/' . $user->user_id .
                    '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';

                return $btn;
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
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'nama' => 'required|string|max:100',
                'username' => 'required|string|min:3|unique:m_users,username',
                'email' => 'required|email|unique:m_users,email',
                'password' => 'required|min:6',
                'role' => 'required|string',
                'no_telepon' => 'nullable|string',
                'alamat' => 'nullable|string',
                'foto_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $data = $request->except('foto_profile');
            $data['password'] = Hash::make($request->password);

            // Handle file upload
            if ($request->hasFile('foto_profile')) {
                $imageName = time().'.'.$request->foto_profile->extension();  
                $request->foto_profile->move(public_path('images/profile'), $imageName);
                $data['foto_profile'] = 'images/profile/'.$imageName;
            }

            UsersModel::create($data);

            return response()->json([
                'status' => true,
                'message' => 'Data user berhasil disimpan'
            ]);
        }

        return redirect('/admin/management-pengguna');
    }

    public function edit_ajax(string $id)
    {
        $user = UsersModel::find($id);
        return view('roles.admin.management-pengguna.edit_ajax', ['user' => $user]);
    }

    public function update_ajax(Request $request, $id)
    {
       if (!$request->ajax() && !$request->wantsJson()) {
            $rules = [
                'nama' => 'required|string|max:100',
                'username' => 'required|string|min:3|unique:m_users,username,'.$id.',user_id',
                'email' => 'required|email|unique:m_users,email,'.$id.',user_id',
                'password' => 'nullable|min:6',
                'role' => 'required|string',
                'no_telepon' => 'nullable|string',
                'alamat' => 'nullable|string',
                'foto_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors()
                ]);
            }

            $user = UsersModel::find($id);
            if ($user) {
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
                    
                    $imageName = time().'.'.$request->foto_profile->extension();  
                    $request->foto_profile->move(public_path('images/profile'), $imageName);
                    $data['foto_profile'] = 'images/profile/'.$imageName;
                }

                $user->update($data);

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
             return redirect('/admin/management-pengguna');
       }
       return response()->json([
        'status' => true,
        'message' => 'Data berhasil diupdate',
        'redirect' => url('/admin/management-pengguna') // Tambahkan URL redirect
    ]);
       
    }

    public function confirm_ajax(string $id)
    {
        $user = UsersModel::find($id);
        return view('roles.admin.management-pengguna.confirm_ajax', ['user' => $user]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $user = UsersModel::find($id);
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
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/admin/management-pengguna');
    }

    public function show_ajax(string $id)
    {
        $user = UsersModel::find($id);
        return view('roles.admin.management-pengguna.show_ajax', ['user' => $user]);
    }

    public function create()
{
    $breadcrumb = (object) [
        'title' => 'Tambah User',
        'list' => ['Home', 'User', 'Tambah']
    ];

    $page = (object) [
        'title' => 'Tambah user baru'
    ];

    $roles = ['admin', 'dosen', 'mahasiswa']; // Daftar role yang tersedia
    $activeMenu = 'user'; // set menu yang sedang aktif

    return view('roles.admin.management-pengguna.create', [
        'breadcrumb' => $breadcrumb,
        'page' => $page,
        'roles' => $roles,
        'activeMenu' => $activeMenu
    ]);
}

public function store(Request $request)
{
    $request->validate([
        'username' => 'required|string|min:3|unique:m_users,username',
        'nama' => 'required|string|max:100',
        'email' => 'required|email|unique:m_users,email',
        'password' => 'required|string|min:5',
        'role' => 'required|string|in:admin,dosen,mahasiswa',
        'no_telepon' => 'nullable|string|max:20',
        'alamat' => 'nullable|string'
    ]);

    UsersModel::create([
        'username' => $request->username,
        'nama' => $request->nama,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'role' => $request->role,
        'no_telepon' => $request->no_telepon,
        'alamat' => $request->alamat,
        'foto_profile' => null // bisa diisi default atau dihandle upload terpisah
    ]);

    return redirect('/admin/management-pengguna')->with('success', 'Data user berhasil disimpan');
}

    // public function import()
    // {
    //     return view('roles.admin.management-pengguna.import');
    // }

    // public function import_ajax(Request $request)
    // {
    //     if ($request->ajax() || $request->wantsJson()) {
    //         $rules = [
    //             'file_user' => ['required', 'mimes:xlsx', 'max:1024']
    //         ];

    //         $validator = Validator::make($request->all(), $rules);

    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'status'   => false,
    //                 'message'  => 'Validasi Gagal',
    //                 'msgField' => $validator->errors()
    //             ]);
    //         }

    //         $file = $request->file('file_user');
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
    //                         'username' => $value['B'],
    //                         'email' => $value['C'],
    //                         'password' => bcrypt($value['D']),
    //                         'role' => $value['E'],
    //                         'no_telepon' => $value['F'] ?? null,
    //                         'alamat' => $value['G'] ?? null,
    //                         'created_at' => now(),
    //                     ];
    //                 }
    //             }

    //             if (count($insert) > 0) {
    //                 UsersModel::insertOrIgnore($insert);
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
    //     $users = UsersModel::select(
    //         'user_id',
    //         'username',
    //         'nama',
    //         'email',
    //         'role',
    //         'no_telepon'
    //     )->orderBy('role')->get();

    //     $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    //     $sheet = $spreadsheet->getActiveSheet();

    //     $sheet->setCellValue('A1', 'No');
    //     $sheet->setCellValue('B1', 'Username');
    //     $sheet->setCellValue('C1', 'Nama');
    //     $sheet->setCellValue('D1', 'Email');
    //     $sheet->setCellValue('E1', 'Role');
    //     $sheet->setCellValue('F1', 'No Telepon');

    //     $sheet->getStyle('A1:F1')->getFont()->setBold(true);

    //     $no = 1;
    //     $baris = 2;
    //     foreach ($users as $user) {
    //         $sheet->setCellValue('A' . $baris, $no);
    //         $sheet->setCellValue('B' . $baris, $user->username);
    //         $sheet->setCellValue('C' . $baris, $user->nama);
    //         $sheet->setCellValue('D' . $baris, $user->email);
    //         $sheet->setCellValue('E' . $baris, $user->role);
    //         $sheet->setCellValue('F' . $baris, $user->no_telepon);
    //         $no++;
    //         $baris++;
    //     }

    //     foreach (range('A', 'F') as $columnID) {
    //         $sheet->getColumnDimension($columnID)->setAutoSize(true);
    //     }

    //     $sheet->setTitle('Data User');
    //     $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    //     $filename = 'Data_User_' . date('Y-m-d_H-i-s') . '.xlsx';

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
    //     $users = UsersModel::select(
    //         'user_id',
    //         'username',
    //         'nama',
    //         'email',
    //         'role',
    //         'no_telepon'
    //     )->orderBy('role')->get();

    //     $pdf = PDF::loadView('roles.admin.management-pengguna.export_pdf', ['users' => $users]);
    //     $pdf->setPaper('A4', 'portrait');
    //     $pdf->setOption("isRemoteEnabled", true);
    //     $pdf->render();

    //     return $pdf->stream('Data User ' . date('Y-m-d H-i-s') . '.pdf');
    // }
}