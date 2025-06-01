<?php

namespace App\Http\Controllers\Api\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WilayahModel;
use App\Models\LowonganMagangModel;
use App\Models\MahasiswaKeahlianModel;
use App\Models\MahasiswaKompetensiModel;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    //

    public function getRegencies(Request $request)
    {
        $search = strtolower($request->query('q', ''));
        $offset = (int) $request->query('offset', 0);
        $limit = (int) $request->query('limit', 50);

        $query = WilayahModel::query();

        if ($search) {
            $query->whereRaw('LOWER(nama) LIKE ?', ['%' . $search . '%']);
        }

        $regencies = $query->select('kode_wilayah as id', 'nama as name', 'latitude', 'longitude')
            ->offset($offset)
            ->limit($limit)
            ->get();

        return response()->json($regencies);
    }

}