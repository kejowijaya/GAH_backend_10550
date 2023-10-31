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


}
