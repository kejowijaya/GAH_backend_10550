<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Reservasi;
use App\Models\Reservasi_Kamar;
use App\Models\Jenis_Kamar;
use App\Models\Kamar;
use App\Models\Season;
use Illuminate\Support\Facades\DB;

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

    public function addReservasi(Request $request){
        $id_booking = 'P' . date('dmy') . '-' . rand(1, 999);
        $status = "Belum DP";

        $tanggal_booking = date('Y-m-d');

        $storeData = $request->all();
        $storeData['id_booking'] = $id_booking;
        $storeData['status'] = $status;
        $storeData['total_harga'] = 0;
        $storeData['tanggal_booking'] = $tanggal_booking;

        $validate = Validator::make($storeData, [
            'id_customer' => 'required',
            'tanggal_check_in' => 'required|before:tanggal_check_out',
            'tanggal_check_out' => 'required|after:tanggal_check_in',
            'dewasa' => 'required',
            'anak' => 'required',
            'nomor_rek' => 'required',
            'jenis_kamar' => 'required|array',
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $reservasi = Reservasi::create($storeData);

        $totalHarga = 0;

        foreach ($storeData['jenis_kamar'] as $jenisKamar) {
            $hargaKamar = Jenis_Kamar::find($jenisKamar['id_jenis'])->harga;
        
            $reservasi_kamar = new Reservasi_Kamar();
            $reservasi_kamar->id_reservasi = $reservasi->id_reservasi;
            $reservasi_kamar->id_jenis = $jenisKamar['id_jenis'];
            $reservasi_kamar->jumlah = $jenisKamar['jumlah'];
        
            $subtotalItem = $jenisKamar['jumlah'] * $hargaKamar;
        
            $totalHarga += $subtotalItem;
        
            $reservasi_kamar->subtotal = $subtotalItem;
            $reservasi_kamar->save();
        }
        
        $reservasi->total_harga = $totalHarga;
        $reservasi->save();

        return response([
            'status' => 'Success',
            'message' => 'Add reservasi success',
            'data' => $reservasi
        ], 200);

    }

    public function addReservasiGrup(Request $request){
        $id_booking = 'G' . date('dmy') . '-' . rand(1, 999);
        $status = "Belum DP";

        $tanggal_booking = date('Y-m-d');

        $storeData = $request->all();
        $storeData['id_booking'] = $id_booking;
        $storeData['status'] = $status;
        $storeData['total_harga'] = 0;
        $storeData['tanggal_booking'] = $tanggal_booking;

        $validate = Validator::make($storeData, [
            'id_customer' => 'required',
            'tanggal_check_in' => 'required|before:tanggal_check_out',
            'tanggal_check_out' => 'required|after:tanggal_check_in',
            'dewasa' => 'required',
            'anak' => 'required',
            'nomor_rek' => 'required',
            'jenis_kamar' => 'required|array',
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $reservasi = Reservasi::create($storeData);

        $totalHarga = 0;

        foreach ($storeData['jenis_kamar'] as $jenisKamar) {
            $hargaKamar = Jenis_Kamar::find($jenisKamar['id_jenis'])->harga;
        
            $reservasi_kamar = new Reservasi_Kamar();
            $reservasi_kamar->id_reservasi = $reservasi->id_reservasi;
            $reservasi_kamar->id_jenis = $jenisKamar['id_jenis'];
            $reservasi_kamar->jumlah = $jenisKamar['jumlah'];
        
            $subtotalItem = $jenisKamar['jumlah'] * $hargaKamar;
        
            $totalHarga += $subtotalItem;
        
            $reservasi_kamar->subtotal = $subtotalItem;
            $reservasi_kamar->save();
        }
        
        $reservasi->total_harga = $totalHarga;
        $reservasi->save();

        return response([
            'status' => 'Success',
            'message' => 'Add reservasi success',
            'data' => $reservasi
        ], 200);

    }

    public function addReservasiKamar($id){
        $storeData = $request->all();

        $validate = Validator::make($storeData, [
            'id_reservasi' => 'required',
            'id_jenis' => 'required',
            'jumlah' => 'required',
            'subtotal' => 'required',
        ]);

        if ($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $reservasi_kamar = Reservasi_Kamar::create($storeData);

        return response([
            'status' => 'Success',
            'message' => 'Add reservasi kamar success',
            'data' => $reservasi_kamar
        ], 200);
    }

    public function getResumeReservasi($id){
        $reservasi = Reservasi::with('reservasi_kamar', 'reservasi_layanan')
        ->where('id_reservasi', $id)
        ->first();

        if (is_null($reservasi)) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Get reservation success',
            'data' => $reservasi
        ]);
    }

    public function bayarReservasi(Request $request, $id){
        $reservasi = Reservasi::find($id);
        $customer = $reservasi->customer;

        if (is_null($reservasi)) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        if($reservasi->status == "Sudah DP"){
            return response()->json(['message' => 'Reservasi sudah dibayar'], 400);
        }

        if($customer->jenis_tamu == "Grup" || $customer->jenis_tamu == "grup"){
            if($request->uang < 0.5 * $reservasi->total_harga){
                return response()->json(['message' => 'Uang kurang'], 400);
            }else{
                $reservasi->status = "Sudah DP";
                $reservasi->tanggal_bayar = now();
                $reservasi->save();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Bayar reservasi success',
                    'data' => $reservasi
                ]);
            }
        }else{
            if($request->uang < $reservasi->total_harga){
                return response()->json(['message' => 'Uang kurang'], 400);
            }else{
                $reservasi->status = "Sudah DP";
                $reservasi->tanggal_bayar = now();
                $reservasi->save();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Bayar reservasi success',
                    'data' => $reservasi
                ]);
            }
        }
    }

    public function batalPesan($id){
        $reservasi = Reservasi::find($id);
        $sevenDaysBeforeCheckIn = date('Y-m-d', strtotime($reservasi->tanggal_check_in . ' - 7 days'));
        $now = now()->toDateString();

        if (is_null($reservasi)) {
            return response()->json(['message' => 'Reservation not found'], 404);
        }

        if($now >= $sevenDaysBeforeCheckIn){
            $reservasi->status = "Batal";
            $reservasi->save();
            return response()->json([    
                'status' => 'success',
                'message' => 'Batal reservasi success, uang hangus',
                'data' => $sevenDaysBeforeCheckIn
            ]);
        }else{
            $reservasi->status = "Batal";
            $reservasi->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Batal reservasi success, uang telah dikembalikan',
                'data' => $sevenDaysBeforeCheckIn
            ]);
        }

    }

    public function ketersediaanKamar(Request $request){
        $tanggal_check_in = $request->tanggal_check_in;
        $tanggal_check_out = $request->tanggal_check_out;

        $jumlahKamarPerJenisKamar = Kamar::select('kamar.id_jenis', DB::raw('count(nomor_kamar) as totalKamar'), 'jenis_kamar.harga', 'jenis_kamar.jenis_kamar as jenisKamar')
        ->join('jenis_kamar', 'kamar.id_jenis', '=', 'jenis_kamar.id_jenis')
        ->groupBy('kamar.id_jenis', 'jenis_kamar.harga', 'jenis_kamar.jenis_kamar')
        ->with('jenis_kamar')->get();

        $seasonKamar = Season::select('tarif_season.perubahan_tarif', 'season.jenis_season as jenis_season', 'tarif_season.id_jenis')
        ->join('tarif_season', 'season.id_season', '=', 'tarif_season.id_season')
        ->where(function ($query) use ($tanggal_check_in, $tanggal_check_out) {
            $query->where('season.tanggal_mulai', '<=', $tanggal_check_in)->where('season.tanggal_selesai', '>', $tanggal_check_out);
        })->get();

        foreach ($jumlahKamarPerJenisKamar as $jumlahKamar) {
            foreach ($seasonKamar as $hargaSeason) {
                if ($hargaSeason->id_jenis == $jumlahKamar->id_jenis) {
                    if ($hargaSeason->jenis_season == 'High') {
                        $Perubahanharga = $jumlahKamar->harga + $hargaSeason->perubahan_tarif;
                    } else if ($hargaSeason->jenis_season == 'Promo'){
                        $Perubahanharga = $jumlahKamar->harga - $hargaSeason->perubahan_tarif;
                    } else {
                        $Perubahanharga = $jumlahKamar->harga;
                    }
                    $jumlahKamar->perubahan_tarif = $hargaSeason->perubahan_tarif;
                    $jumlahKamar->jenis_season = $hargaSeason->jenis_season;
                    $jumlahKamar->harga = $Perubahanharga;
                }
            }

            if (is_null($jumlahKamar->jenis_season)) {
                $jumlahKamar->perubahan_tarif = null;
                $jumlahKamar->jenis_season = 'Normal';
                $jumlahKamar->harga = $jumlahKamar->harga;
            }
        }

        $jmlKamarSudahDipakai = Reservasi::where(function ($query) use ($tanggal_check_in, $tanggal_check_out) {
            $query->where('tanggal_check_in', '<', $tanggal_check_in)
                ->where('tanggal_check_out', '>', $tanggal_check_in);
        })->orWhere(function ($query) use ($tanggal_check_in, $tanggal_check_out) {
            $query->where('tanggal_check_in', '<', $tanggal_check_out)
                ->where('tanggal_check_out', '>', $tanggal_check_out);
        })->orWhere(function ($query) use ($tanggal_check_in, $tanggal_check_out) {
            $query->where('tanggal_check_in', '>=', $tanggal_check_in)
                ->where('tanggal_check_out', '<=', $tanggal_check_out);
        })->where(function ($query) {
            $query->where('status', 'Sudah DP')
                ->orWhere('status', 'Belum DP');
        })->with('reservasi_kamar')->get();

        if ($jmlKamarSudahDipakai !== null) {
            if ($jmlKamarSudahDipakai->count() > 0) {
                foreach ($jmlKamarSudahDipakai as $reservasi) {
                    foreach ($reservasi->reservasi_kamar as $rooms) {
                        $idJK = $rooms->id_jenis;
                        $objJK = $jumlahKamarPerJenisKamar->first(function ($item) use ($idJK) {
                            return $item->id_jenis === $idJK;
                        });
                        if ($objJK && $objJK->totalKamar > 0) {
                            $objJK->totalKamar -= $rooms->jumlah;

                            if ($objJK->totalKamar < 0) {
                                $objJK->totalKamar = 0;
                            }
                        }
                    }
                }
                return response()->json(['status' => 'F', 'message' => 'Sudah ada reservasi lain di tanggal tersebut!', 'data' => $jumlahKamarPerJenisKamar], 200);
            } else {
                return response()->json(['status' => 'T', 'message' => 'Belum ada reservasi', 'data' => $jumlahKamarPerJenisKamar], 200);
            }
        } else {
            return response()->json(['status' => 'E', 'message' => 'Terjadi kesalahan dalam pengambilan data'], 500);
        }
    }

}
