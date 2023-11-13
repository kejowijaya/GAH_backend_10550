<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Fasilitas;

class FasilitasController extends Controller
{
    public function index()
    {
        $fasilitass = Fasilitas::all();

        if(count($fasilitass) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $fasilitass
            ], 200);
        }

        return response([
            'message' => 'Empty',
            'data' => null
        ], 400);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'nama_fasilitas' => 'required|unique:fasilitas',
            'harga' => 'required',
            'satuan' => 'required',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
            
        $fasilitas = Fasilitas::create($storeData);
        return response([
            'message' => 'Add fasilitas Success',
            'data' => $fasilitas
        ], 200);
    }

    public function show($id)
    {
        $fasilitas = Fasilitas::find($id);

        if(!is_null($fasilitas)) {
            return response([
                'message' => 'Retrieve fasilitas Success',
                'data' => $fasilitas
            ], 200);
        }

        return response([
            'message' => 'Fasilitas Not Found',
            'data' => null
        ], 404);
    }

    public function update(Request $request, $id)
    {
        $fasilitas = Fasilitas::find($id);
        if(is_null($fasilitas)){
            return response([
                'message' => 'Fasilitas Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_fasilitas' => 'required',
            'harga' => 'required',
            'satuan' => 'required',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
        
        $fasilitas->nama_fasilitas = $updateData['nama_fasilitas'];
        $fasilitas->harga = $updateData['harga'];
        $fasilitas->satuan = $updateData['satuan'];

        if($fasilitas->save()){
            return response([
                'message' => 'Update Fasilitas Success',
                'data' => $fasilitas
            ], 200);
        }

        return response([
            'message' => 'Update Fasilitas Failed',
            'data' => null
        ], 400);
    }

    public function destroy($id)
    {
        $fasilitas = Fasilitas::find($id);

        if(is_null($fasilitas)) {
            return response([
                'message' => 'Fasilitas Not Found',
                'data' => null
            ], 404);
        }

        if($fasilitas->delete()) {
            return response([
                'message' => 'Delete Fasilitas Success',
                'data' => $fasilitas
            ], 200);
        }

        return response([
            'message' => 'Delete Fasilitas Failed',
            'data' => null
        ], 400);
    }
}