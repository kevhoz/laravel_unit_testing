<?php

namespace App\Http\Controllers;

use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OutletController extends Controller
{
    // Display a listing of the outlet.
    public function index()
    {
        $outlets = Outlet::all();
        return response()->json($outlets);
    }

    // Store a newly created outlet in storage.
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_outlet' => 'required|string|max:255',
            'lokasi_outlet' => 'required|string|max:255',
            'pic_outlet' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $outlet = Outlet::create($validator->validated());

        return response()->json($outlet, 201);
    }

    // Display the specified outlet.
    public function show(Outlet $outlet)
    {
        return response()->json($outlet);
    }

    // Update the specified outlet in storage.
    public function update(Request $request, Outlet $outlet)
    {
        $validator = Validator::make($request->all(), [
            'nama_outlet' => 'required|string|max:255',
            'lokasi_outlet' => 'required|string|max:255',
            'pic_outlet' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $outlet->update($validator->validated());

        return response()->json($outlet);
    }

    // Remove the specified outlet from storage.
    public function destroy(Outlet $outlet)
    {
        $outlet->delete();
        return response()->json(null, 204);
    }
}
