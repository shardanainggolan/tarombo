<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Marga;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class MargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Marga::latest();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '
                        <div class="d-flex align-items-center">
                            <a href="' . route('admin.margas.edit', $row->id) . '" class="btn btn-icon btn-label-info waves-effect me-2">
                                <i class="ti ti-pencil"></i>
                            </a>
                            <button type="button" id="' . $row->id . '" class="delete-record btn btn-icon btn-label-danger waves-effect">
                                <i class="ti ti-trash"></i>
                            </button>
                        </div>
                    ';
                    return $actionBtn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        return view('admin.pages.margas.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.margas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:margas,name',
            'description' => 'nullable|string'
        ]);

        Marga::create($request->all());

        return redirect()->route('admin.margas.index')
            ->with('success', 'Marga berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Marga $marga)
    {
        return view('admin.pages.margas.edit', compact('marga'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Marga $marga)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:margas,name,' . $marga->id,
            'description' => 'nullable|string'
        ]);

        $marga->update($request->all());

        return redirect()->route('admin.margas.index')
            ->with('success', 'Marga berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Marga $marga)
    {
        try {
            $marga->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Marga berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus marga. ' . $e->getMessage()
            ], 422);
        }
    }
}
