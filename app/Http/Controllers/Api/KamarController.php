<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Kamar; 

class KamarController extends Controller
{
    public function index()
    {
        $kamars = Kamar::with('jenis_kamar')->get();

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
            'nomor_kamar' => 'required|unique:kamar',
            'id_jenis' => 'required',
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);
            
        $kamar = Kamar::create($storeData);
        $kamar->nomor_kamar = $request->nomor_kamar;
        
        return response([
            'message' => 'Add kamar Success',
            'data' => $kamar
        ], 200);
    }

    public function show($id)
    {
        $kamar = Kamar::with('jenis_kamar')->find($id);

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
        $kamar = Kamar::find($id);
        if (is_null($kamar)) {
            return response([
                'message' => 'Kamar Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'id_jenis' => 'required',
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $kamar->id_jenis = $updateData['id_jenis'];

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
        $kamar = Kamar::find($id); 

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
