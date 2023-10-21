<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Reservasi;

class ReservasiController extends Controller
{
    public function getDetailTransaksi($id)
    {
        $reservasi = Reservasi::with('customer', 'pegawai')->where('id_reservasi', $id)->first();

        if (is_null($reservasi)) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Get reservation success',
            'data' => $reservasi
        ]);
    }

    public function getRiwayatTransaksi($id)
    {
        $reservasi = Reservasi::where('id_customer', $id)->get();

        if ($reservasi->isEmpty()) {
            return response()->json(['message' => 'No reservations found for this customer'], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Get riwayat transaksi success',
            'data' => $reservasi
        ]);
    }

}
