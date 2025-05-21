<?php

namespace App\Http\Controllers;

use App\Models\PerusahaanModel;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Pagination\Paginator;

class LandingPageController extends Controller
{
    public function index(Request $request): View|\Illuminate\Http\JsonResponse
    {
        $companies = PerusahaanModel::paginate(3);

        if ($request->ajax()) {
            $cards = view('component.mitra-cards', compact('companies'))->render();
            $pagination = $companies->withQueryString()->links('pagination::bootstrap-5')->render();
            return response()->json([
                'cards' => $cards,
            ]);
        }

        return view('landing-page', compact('companies'));
    }
}