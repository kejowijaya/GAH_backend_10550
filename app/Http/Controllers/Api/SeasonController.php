<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Season;

class SeasonController extends Controller
{
    public function index()
    {
        $seasons = Season::all();

        if(count($seasons) > 0){
            return response([
                'message' => 'Retrieve All Success',
                'data' => $seasons
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
            'nama_season' => 'required|unique:season',
            'tanggal_mulai' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $twoMonthsFromNow = now()->addMonths(2);
                    if ($value < $twoMonthsFromNow) {
                        $fail("$attribute harus lebih dari 2 bulan dari tanggal sekarang.");
                    }
                },
            ],
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'jenis_season' => 'required',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
            
        $season = Season::create($storeData);
        return response([
            'message' => 'Add season Success',
            'data' => $season
        ], 200);
    }

    public function show($id)
    {
        $season = Season::find($id);

        if(!is_null($season)) {
            return response([
                'message' => 'Retrieve season Success',
                'data' => $season
            ], 200);
        }

        return response([
            'message' => 'Season Not Found',
            'data' => null
        ], 404);
    }

    public function update(Request $request, $id)
    {
        $season = Season::find($id);
        if(is_null($season)){
            return response([
                'message' => 'Season Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_season' => 'required|unique:season',
            'tanggal_mulai' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $twoMonthsFromNow = now()->addMonths(2);
                    if ($value < $twoMonthsFromNow) {
                        $fail("$attribute harus lebih dari 2 bulan dari tanggal sekarang.");
                    }
                },
            ],
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'jenis_season' => 'required',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
        
        $season->nama_season = $updateData['nama_season'];
        $season->tanggal_mulai = $updateData['tanggal_mulai'];
        $season->tanggal_selesai = $updateData['tanggal_selesai'];
        $season->jenis_season = $updateData['jenis_season'];

        if($season->save()){
            return response([
                'message' => 'Update Season Success',
                'data' => $season
            ], 200);
        }

        return response([
            'message' => 'Update Season Failed',
            'data' => null
        ], 400);
    }

    public function destroy($id)
    {
        $season = Season::find($id);

        if(is_null($season)) {
            return response([
                'message' => 'Season Not Found',
                'data' => null
            ], 404);
        }

        if($season->delete()) {
            return response([
                'message' => 'Delete Season Success',
                'data' => $season
            ], 200);
        }

        return response([
            'message' => 'Delete Season Failed',
            'data' => null
        ], 400);
    }
}