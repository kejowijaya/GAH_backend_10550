<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Jenis_Kamar;

class JenisKamarController extends Controller
{
    public function show($id)
    {
        $jenis_kamar = Jenis_Kamar::find($id);

        if (!is_null($jenis_kamar)) {
            return response([
                'message' => 'Retrieve jenis kamar Success',
                'data' => $jenis_kamar
            ], 200);
        }

        return response([
            'message' => 'Jenis kamar Not Found',
            'data' => null
        ], 404);
    }

    public function index()
    {
        $jenis_kamar = Jenis_Kamar::all();

        if(count($jenis_kamar) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $jenis_kamar
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
            'jenis_kamar' => 'required|unique:jenis_kamar',
            'harga' => 'required',
            'fasilitas' => 'required',
            'tipe_ranjang' => 'required',
            'luas_kamar' => 'required',
            'kapasitas' => 'required',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
            
        $jenis_kamar = Jenis_Kamar::create($storeData);
        return response([
            'message' => 'Add jenis kamar Success',
            'data' => $jenis_kamar
        ], 200);
    }

    //update
    public function update(Request $request, $id)
    {
        $jenis_kamar = Jenis_Kamar::find($id);
        if(is_null($jenis_kamar)){
            return response([
                'message' => 'Jenis kamar Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'jenis_kamar' => ['required', Rule::unique('jenis_kamar')->ignore($jenis_kamar)],
            'harga' => 'required',
            'fasilitas' => 'required',
            'tipe_ranjang' => 'required',
            'luas_kamar' => 'required',
            'kapasitas' => 'required',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
        
        $jenis_kamar->jenis_kamar = $updateData['jenis_kamar'];
        $jenis_kamar->harga = $updateData['harga'];
        $jenis_kamar->fasilitas = $updateData['fasilitas'];
        $jenis_kamar->tipe_ranjang = $updateData['tipe_ranjang'];
        $jenis_kamar->luas_kamar = $updateData['luas_kamar'];
        $jenis_kamar->kapasitas = $updateData['kapasitas'];

        if($jenis_kamar->save()){
            return response([
                'message' => 'Update jenis kamar Success',
                'data' => $jenis_kamar
            ], 200);
        }
        return response([
            'message' => 'Update jenis kamar Failed',
            'data' => null
        ], 400);
    }

    //delete
    public function destroy($id)
    {
        $jenis_kamar = Jenis_Kamar::find($id);

        if(is_null($jenis_kamar)) {
            return response([
                'message' => 'Jenis kamar Not Found',
                'data' => null
            ], 404);
        }

        if($jenis_kamar->delete()) {
            return response([
                'message' => 'Delete jenis kamar Success',
                'data' => $jenis_kamar,
            ], 200);
        }
        return response([
            'message' => 'Delete jenis kamar Failed',
            'data' => null,
        ], 400);
    }





}
