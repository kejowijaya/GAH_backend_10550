<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Reservasi_Layanan;
use App\Models\Fasilitas;

class ReservasiLayanan extends Controller
{
    public function tambahLayanan(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'id_reservasi' => 'required',
            'fasilitas' => 'required|array',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $reservasi_layanan = Reservasi_Layanan::create($storeData);

        $totalHarga = 0;

        foreach ($storeData['fasilitas'] as $fasilitas) {
            $hargaFasilitas = Fasilitas::find($fasilitas['id_fasilitas'])->harga;
        
            $reservasi_layanan = new Reservasi_Layanan();
            $reservasi_layanan->id_reservasi = $reservasi->id_reservasi;
            $reservasi_layanan->id_fasilitas = $fasilitas['id_fasilitas'];
            $reservasi_layanan->jumlah = $fasilitas['jumlah'];
        
            $subtotalItem = $fasilitas['jumlah'] * $hargaFasilitas;
        
            $totalHarga += $subtotalItem;
        
            $reservasi_layanan->total_harga = $subtotalItem;
            $reservasi_layanan->save();
        }
        
        return response([
            'message' => 'Add Reservasi Layanan Success',
            'data' => $reservasi_layanan
        ], 200);
    }
}
