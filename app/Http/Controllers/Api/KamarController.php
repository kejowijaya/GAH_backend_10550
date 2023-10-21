<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Jenis_Kamar; 

class KamarController extends Controller
{
    public function index()
    {
        $kamars = Jenis_Kamar::all();

        if (count($kamars) > 0) {
            return response([
                'message' => 'Retrieve All Success',
                'data' => $kamars
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
            'jenis_kamar' => 'required',
            'tipe_ranjang' => 'required',
            'fasilitas' => 'required',
            'harga' => 'required',
            'luas_kamar' => 'required',
            'kapasitas' => 'required',
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $kamar = Jenis_Kamar::create($storeData); // Update the model name
        return response([
            'message' => 'Add kamar Success',
            'data' => $kamar
        ], 200);
    }

    public function show($id)
    {
        $kamar = Jenis_Kamar::find($id); 

        if (!is_null($kamar)) {
            return response([
                'message' => 'Retrieve kamar Success',
                'data' => $kamar
            ], 200);
        }

        return response([
            'message' => 'Kamar Not Found',
            'data' => null
        ], 404);
    }

    public function update(Request $request, $id)
    {
        $kamar = Jenis_Kamar::find($id); // Update the model name
        if (is_null($kamar)) {
            return response([
                'message' => 'Kamar Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'jenis_kamar' => 'required',
            'tipe_ranjang' => 'required',
            'fasilitas' => 'required',
            'harga' => 'required',
            'luas_kamar' => 'required',
            'kapasitas' => 'required',
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $kamar->jenis_kamar = $updateData['jenis_kamar'];
        $kamar->tipe_ranjang = $updateData['tipe_ranjang'];
        $kamar->fasilitas = $updateData['fasilitas'];
        $kamar->harga = $updateData['harga'];
        $kamar->luas_kamar = $updateData['luas_kamar'];
        $kamar->kapasitas = $updateData['kapasitas'];

        if ($kamar->save()) {
            return response([
                'message' => 'Update Kamar Success',
                'data' => $kamar
            ], 200);
        }

        return response([
            'message' => 'Update Kamar Failed',
            'data' => null
        ], 400);
    }

    public function destroy($id)
    {
        $kamar = Jenis_Kamar::find($id); // Update the model name

        if (is_null($kamar)) {
            return response([
                'message' => 'Kamar Not Found',
                'data' => null
            ], 404);
        }

        if ($kamar->delete()) {
            return response([
                'message' => 'Delete Kamar Success',
                'data' => $kamar
            ], 200);
        }

        return response([
            'message' => 'Delete Kamar Failed',
            'data' => null
        ], 400);
    }
}
