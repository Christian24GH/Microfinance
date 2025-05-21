<?php

namespace App\Http\Controllers;

use App\Models\ClaimType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClaimTypeController extends Controller
{
    public function index()
    {
        $types = ClaimType::all();
        return response()->json(['status' => 'success', 'types' => $types]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }
        $type = ClaimType::create($request->all());
        return response()->json(['status' => 'success', 'type' => $type]);
    }

    public function show($id)
    {
        $type = ClaimType::findOrFail($id);
        return response()->json(['status' => 'success', 'type' => $type]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 422);
        }
        $type = ClaimType::findOrFail($id);
        $type->update($request->all());
        return response()->json(['status' => 'success', 'type' => $type]);
    }

    public function destroy($id)
    {
        $type = ClaimType::findOrFail($id);
        $type->delete();
        return response()->json(['status' => 'success']);
    }
}
