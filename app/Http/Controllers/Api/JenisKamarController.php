<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Jenis_Kamar;

class KamarController extends Controller
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


}
