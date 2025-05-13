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
        // Menyiapkan breadcrumb
        $breadcrumb = (object) [
            'title' => 'Daftar Program Studi',
            'list' => ['Home', 'Program Studi']
        ];

        // Menyiapkan halaman
        $page = (object) [
            'title' => 'Daftar program studi yang terdaftar dalam sistem'
        ];

        // Menetapkan menu aktif
        $activeMenu = 'program_studi';

        // Mengembalikan view dengan data
        return view('roles.admin.management-prodi.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }


    public function list(Request $request)
    {
        $programstudi = ProgramStudiModel::select('id', 'nama');

        // Filter berdasarkan nama jika ada (optional)
        if ($request->nama) {
            $programstudi->where('nama', 'like', '%' . $request->nama . '%');
        }

        return DataTables::of($programstudi)
            ->addIndexColumn()
            ->addColumn('aksi', function ($programstudi) {
                $btn = '<button onclick="modalAction(\'' . url('/admin/management-prodi/' . $programstudi->id .
                    '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/admin/management-prodi/' . $programstudi->id .
                    '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/admin/management-prodi/' . $programstudi->id .
                    '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button>';

                return $btn;
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
        // Validasi input
        $validated = $request->validate([
            'nama' => 'required|min:3|max:100',
        ]);

        // Simpan data program studi
        try {
            $programStudi = new ProgramStudiModel();
            $programStudi->nama = $request->nama;
            $programStudi->save();

            return response()->json([
                'status' => true,
                'message' => 'Program Studi berhasil ditambahkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'msgField' => []
            ]);
        }
    }

    public function edit_ajax($id)
    {
        $programStudi = ProgramStudiModel::find($id);
        return view('roles.admin.management-prodi.edit_ajax', compact('programStudi'));
    }

    public function update_ajax(Request $request, $id)
    {
        // Validasi data yang masuk
        $validatedData = $request->validate([
            'nama' => 'required|min:3|max:100',
        ]);

        // Mencari data program studi yang ingin diupdate
        $programStudi = ProgramStudiModel::find($id);

        // Jika data tidak ditemukan, kembalikan respons error
        if (!$programStudi) {
            return response()->json([
                'status' => false,
                'message' => 'Program Studi tidak ditemukan',
            ]);
        }

        // Update data program studi
        $programStudi->nama = $validatedData['nama'];
        $programStudi->save();

        // Kembalikan respons sukses dengan redirect URL
        return response()->json([
            'status' => true,
            'message' => 'Data berhasil diperbarui',
            'redirect' => url('/admin/management-prodi'),  // Redirect ke daftar program studi
        ]);
    }

    public function confirm_ajax(string $id)
    {
        $programStudi = ProgramStudiModel::find($id);
        return view('roles.admin.management-prodi.confirm_ajax', ['programStudi' => $programStudi]);
    }

    public function delete_ajax($id)
    {
        $programStudi = ProgramStudiModel::find($id);

        if ($programStudi) {
            try {
                $programStudi->delete();

                return response()->json([
                    'status' => true,
                    'message' => 'Program Studi berhasil dihapus.'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat menghapus data.'
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'message' => 'Data Program Studi tidak ditemukan.'
        ]);
    }

    public function show_ajax($id)
    {
        $programStudi = ProgramStudiModel::find($id);
        return view('roles.admin.management-prodi.show_ajax', compact('programStudi'));
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Program Studi',
            'list' => ['Home', 'Program Studi', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah program studi baru'
        ];

        $activeMenu = 'programStudi'; // set menu yang sedang aktif

        return view('roles.admin.management-prodi.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu
        ]);
    }
}
