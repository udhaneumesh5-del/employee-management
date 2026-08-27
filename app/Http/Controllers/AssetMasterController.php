<?php

namespace App\Http\Controllers;

use App\Models\AssetMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssetMasterController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('assets_master');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('asset_code', 'LIKE', "%{$search}%")
                  ->orWhere('asset_type', 'LIKE', "%{$search}%")
                  ->orWhere('company_name', 'LIKE', "%{$search}%")
                  ->orWhere('model', 'LIKE', "%{$search}%");
            });
        }

        $assets = $query->orderBy('id', 'desc')->paginate(10);
        return view('asset-master.index', compact('assets'));
    }

    // Create Method - Admin & HR Only
    public function create()
    {
        return view('asset-master.create');
    }

    // Store Method - Admin & HR Only
    public function store(Request $request)
    {
        $request->validate([
            'asset_code' => 'required|unique:assets_master|max:50',
            'asset_type' => 'required|max:50',
            'company_name' => 'required|max:100',
            'model' => 'required|max:100',
            'condition' => 'required|in:Good,Damaged',
            'remarks' => 'nullable|string'
        ]);

        DB::table('assets_master')->insert([
            'asset_code' => $request->asset_code,
            'asset_type' => $request->asset_type,
            'company_name' => $request->company_name,
            'model' => $request->model,
            'condition' => $request->condition,
            'status' => 'Available',
            'remarks' => $request->remarks,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return redirect()->route('asset-master.index')
            ->with('success', 'Asset added successfully!');
    }

    // Edit Method - Admin & HR Only
    public function edit($id)
    {
        $asset = DB::table('assets_master')->where('id', $id)->first();
        return view('asset-master.edit', compact('asset'));
    }

    // Update Method - Admin & HR Only
    public function update(Request $request, $id)
    {
        $request->validate([
            'asset_code' => 'required|max:50|unique:assets_master,asset_code,' . $id,
            'asset_type' => 'required|max:50',
            'company_name' => 'required|max:100',
            'model' => 'required|max:100',
            'condition' => 'required|in:Good,Damaged',
            'remarks' => 'nullable|string'
        ]);

        DB::table('assets_master')
            ->where('id', $id)
            ->update([
                'asset_code' => $request->asset_code,
                'asset_type' => $request->asset_type,
                'company_name' => $request->company_name,
                'model' => $request->model,
                'condition' => $request->condition,
                'remarks' => $request->remarks,
                'updated_at' => now()
            ]);

        return redirect()->route('asset-master.index')
            ->with('success', 'Asset updated successfully!');
    }

    // Destroy Method - Admin & HR Only
    public function destroy($id)
    {
        $asset = DB::table('assets_master')->where('id', $id)->first();

        if ($asset->status == 'Issued') {
            return redirect()->back()
                ->with('error', 'Cannot delete issued asset!');
        }

        DB::table('assets_master')->where('id', $id)->delete();

        return redirect()->route('asset-master.index')
            ->with('success', 'Asset deleted successfully!');
    }

    // Show Method - All Roles (View only)
    public function show($id)
    {
        $asset = DB::table('assets_master')->where('id', $id)->first();
        
        if (!$asset) {
            return redirect()->route('asset-master.index')
                ->with('error', 'Asset not found.');
        }
        
        return view('asset-master.show', compact('asset'));
    }

    // Export CSV - All Roles
    public function exportCSV()
    {
        $assets = DB::table('assets_master')->get();

        $filename = 'assets_' . date('Y_m_d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($assets) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");

            fputcsv($file, [
                'Asset Code', 'Asset Type', 'Company Name', 'Model', 'Condition', 'Status'
            ]);

            foreach ($assets as $asset) {
                fputcsv($file, [
                    $asset->asset_code,
                    $asset->asset_type,
                    $asset->company_name,
                    $asset->model,
                    $asset->condition,
                    $asset->status
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}