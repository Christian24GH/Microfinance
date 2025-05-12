<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Ui\Presets\React;

class AssetsAssets extends Controller
{
    public function index(){
        $viewdata = $this->init();
        $viewdata += [
            'pageTitle' => 'Assets',
            'hasBtn'=>'addAssetModal'
        ];
        $assets = DB::table('assets')->get();
        return view('asset.assets.index', $viewdata)
            ->with('assets', $assets);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_tag' => 'required|string|max:20|unique:assets,asset_tag',
            'category' => 'required|in:vehicle,electronic,furniture,building,others',
            'status' => 'required|in:active,under repair,decommissioned',
            'purchase_date' => 'required|date',
        ]);

        DB::table('assets')->insert([
            'asset_tag' => $validated['asset_tag'],
            'category' => $validated['category'],
            'status' => $validated['status'],
            'purchase_date' => $validated['purchase_date'],
        ]);

        return redirect()->back()->with('success', 'Asset added successfully.');
    }

    public function update(Request $request){
        //dd($request);
        $request->validate([
            'status'=>'nullable|string|min:1',
            'category'=>'nullable|string|min:1',
            'id'=>'required|exists:assets,id'
        ]);
        DB::transaction(function()use($request){
            DB::table('assets')->where('id', $request->id)
                ->update([
                    'status'=>$request->status,
                    'category'=>$request->category
                ]);
        });
        return back();
    }

    public function destroy(Request $request){
        $request->validate([
            'ids' => 'required',
        ]);
        DB::transaction(function () use($request){
            foreach($request->ids as $id){
                DB::table('assets')->delete($id);
            }
        });
        return back();
    }
}
