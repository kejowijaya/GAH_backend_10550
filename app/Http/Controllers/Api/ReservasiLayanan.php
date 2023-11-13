<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Reservasi_Layanan;

class ReservasiLayanan extends Controller
{
    public function tambahLayanan(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'id_reservasi' => 'required',
            'id_fasilitas' => 'required',
            'tanggal_pakai' => 'required|date',
            'total_harga' => 'required',
            'jumlah' => 'required',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $reservasi_layanan = Reservasi_Layanan::create($storeData);

        return response([
            'message' => 'Add Reservasi Layanan Success',
            'data' => $reservasi_layanan
        ], 200);
    }
}
