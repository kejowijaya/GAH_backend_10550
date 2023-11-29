<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Reservasi_Layanan;
use App\Models\Reservasi;
use App\Models\Fasilitas;
use App\Models\Invoice;

class ReservasiLayanan extends Controller
{
    public function tambahLayanan(Request $request)
    {
        $reservasi = Reservasi::find($request->id_reservasi);
        $invoice = Invoice::where('id_reservasi', $request->id_reservasi)->first();
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'id_reservasi' => 'required',
            'fasilitas' => 'required|array',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $totalHarga = 0;

        foreach ($storeData['fasilitas'] as $fasilitas) {
            $hargaFasilitas = Fasilitas::find($fasilitas['id_fasilitas'])->harga;
        
            $reservasi_layanan = new Reservasi_Layanan();

            $reservasi_layanan->id_reservasi = $request->id_reservasi;
            $reservasi_layanan->id_fasilitas = $fasilitas['id_fasilitas'];
            $reservasi_layanan->jumlah = $fasilitas['jumlah'];
        
            $subtotalItem = $fasilitas['jumlah'] * $hargaFasilitas;
        
            $totalHarga += $subtotalItem;

            $reservasi_layanan->total_harga = $subtotalItem;
            $reservasi_layanan->save();
        }

        if($totalHarga > $reservasi->deposit){
            $reservasi->deposit = 0;
            $reservasi->total_harga += $totalHarga;
        }else{
            if($totalHarga < 300000){
                $reservasi->deposit = $reservasi->deposit - $totalHarga;
            }
            else{
                $reservasi->deposit = 0;
                $reservasi->total_harga += $totalHarga;
            }
        }

        $reservasi->total_harga += $totalHarga;
        $invoice->pajak = 0.1 * $totalHarga;

        $invoice->save();
        $reservasi->save();
        
        return response([
            'message' => 'Add Reservasi Layanan Success',
        ], 200);
    }
}
