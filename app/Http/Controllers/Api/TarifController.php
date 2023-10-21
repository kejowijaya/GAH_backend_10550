<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Tarif;

class TarifController extends Controller
{
    public function index()
    {
        $tarif = Tarif::all();

        if(count($tarif) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $tarif
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
            'id_season' => 'required',
            'id_jenis' => 'required',
            'perubahan_tarif' => 'required',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
            
        $tarif = Tarif::create($storeData);
        return response([
            'message' => 'Add tarif Success',
            'data' => $tarif
        ], 200);
    }

    public function show($id)
    {
        $tarif = Tarif::find($id);

        if(!is_null($tarif)) {
            return response([
                'message' => 'Retrieve tarif Success',
                'data' => $tarif
            ], 200);
        }

        return response([
            'message' => 'Tarif Not Found',
            'data' => null
        ], 404);
    }

    public function update(Request $request, $id)
    {
        $tarif = Tarif::find($id);
        if(is_null($tarif)){
            return response([
                'message' => 'Tarif Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_season' => 'required',
            'id_jenis' => 'required',
            'perubahan_tarif' => 'required',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
        
        $tarif->id_season = $updateData['id_season'];
        $tarif->id_jenis = $updateData['id_jenis'];
        $tarif->perubahan_tarif = $updateData['perubahan_tarif'];

        if($tarif->save()){
            return response([
                'message' => 'Update Tarif Success',
                'data' => $tarif
            ], 200);
        }

        return response([
            'message' => 'Update Tarif Failed',
            'data' => null
        ], 400);
    }

    public function destroy($id)
    {
        $tarif = Tarif::find($id);

        if(is_null($tarif)) {
            return response([
                'message' => 'Tarif Not Found',
                'data' => null
            ], 404);
        }

        if($tarif->delete()) {
            return response([
                'message' => 'Delete Tarif Success',
                'data' => $tarif
            ], 200);
        }

        return response([
            'message' => 'Delete Tarif Failed',
            'data' => null
        ], 400);
    }
}