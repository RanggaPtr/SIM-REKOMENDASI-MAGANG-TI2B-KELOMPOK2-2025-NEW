<?php

namespace App\Http\Controllers\Api\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WilayahModel;

class DashboardController extends Controller
{
    //

    public function getRegencies(Request $request)
    {
        $search = strtolower($request->query('q', ''));

        $query = WilayahModel::query();

        if ($search) {
            $query->whereRaw('LOWER(nama) LIKE ?', ['%' . $search . '%']);
        }

        $regencies = $query->select('kode_wilayah as id', 'nama as name', 'latitude', 'longitude')
            ->limit(50)
            ->get();

        return response()->json($regencies);
    }
}
