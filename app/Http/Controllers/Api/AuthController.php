<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Models\Pegawai;
use Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $registrationData = $request->all();

        $validate = Validator::make($registrationData, [
            'no_identitas' => 'required',
            'nama' => 'required|string',
            'nomor_telepon' => 'required|string|min:10|max:13',
            'email' => 'required|string|email|unique:customer',
            'password' => 'required',
            'alamat' => 'required|string',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
               
        $registrationData['password'] = bcrypt($request->password);

        $registrationData['jenis_tamu'] = 'customer';
        $customer = Customer::create($registrationData);

        event(new Registered($customer));

        return response()->json([
            'message' => 'Successfully created customer!',
            'data' => $customer
        ], 200);
    }

    public function login(Request $request) {
        $loginData = $request->all();
    
        $validate = Validator::make($loginData, [
            'email' => 'required|email:rfc,dns',
            'password' => 'required',
        ]);
    
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
    
        if (!Auth::guard('web')->attempt($loginData)) {
            return response(['message' => 'Invalid Credentials'], 401);
        }        
    
        $customer = Customer::where('email', $request->email)->first();
        $token = $customer->createToken('Authentication Token')->plainTextToken;
    
        return response([
            'message' => 'Authenticated',
            'customer' => $customer,
            'token' => $token,
        ]);
    }

    public function logout (Request $request){
        $request->customer()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::find($id);
        if(is_null($customer)){
            return response([
                'message' => 'Customer Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'no_identitas' => 'required',
            'nama' => 'required|string',
            'nomor_telepon' => 'required|string|min:10|max:13',
            'email' => 'required|string|email',
            'password' => 'required',
            'alamat' => 'required|string',
            'jenis_tamu' => 'required|string',

        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $updateData['password'] = bcrypt($request->password);

        $customer->no_identitas = $updateData['no_identitas'];
        $customer->nama = $updateData['nama'];
        $customer->nomor_telepon = $updateData['nomor_telepon'];
        $customer->email = $updateData['email'];
        $customer->password = $updateData['password'];
        $customer->alamat = $updateData['alamat'];
        $customer->jenis_tamu = $updateData['jenis_tamu'];

        if($customer->save()){
            return response([
                'message' => 'Update Customer Success',
                'data' => $customer
            ], 200);
        }

        
        return response([
            'message' => 'Update Customer Failed',
            'data' => null
        ], 400);
    }

    public function show($id)
    {
        $customer = Customer::find($id);
        if(is_null($customer)){
            return response([
                'message' => 'Customer Not Found',
                'data' => null
            ], 404);
        }
        return response([
            'message' => 'Customer Found',
            'data' => $customer
        ], 200);
    }

    public function registerPegawai(Request $request)
    {
        $registrationData = $request->all();

        $validate = Validator::make($registrationData, [
            'role' => 'required',
            'username' => 'required|string|unique:pegawai',
            'password' => 'required',
            'nama_pegawai' => 'required|string',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
               
        $registrationData['password'] = bcrypt($request->password);

        $pegawai = Pegawai::create($registrationData);

        event(new Registered($pegawai));

        return response()->json([
            'message' => 'Successfully created pegawai!',
            'data' => $pegawai
        ], 200);
    }

    public function loginPegawai(Request $request) {
        $loginData = $request->all();
    
        $validate = Validator::make($loginData, [
            'email' => 'required|string|unique:pegawai',
            'password' => 'required',
        ]);
    
        if ($validate->fails()) {
            return response(['message' => $validate->errors()], 400);
        }
    
        if (!Auth::guard('pegawai')->attempt($loginData)) {
            return response(['message' => 'Invalid Credentials'], 401);
        }
    
        $pegawai = Pegawai::where('email', $request->email)->first();
        $token = $pegawai->createToken('Authentication Token')->plainTextToken;
    
        return response([
            'message' => 'Authenticated',
            'pegawai' => $pegawai,
            'token' => $token,
        ]);
    }   
    
    public function showPegawai($id)
    {
        $pegawai = Pegawai::find($id);
        if(is_null($pegawai)){
            return response([
                'message' => 'Pegawai Not Found',
                'data' => null
            ], 404);
        }
        return response([
            'message' => 'Pegawai Found',
            'data' => $pegawai
        ], 200);

    }

    public function changePassword(Request $request, $email)
    {
        $customer = Customer::where('email', $email)->first();
        if(is_null($customer)){
            return response([
                'message' => 'Customer Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'password' => 'required',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);
            
        $updateData['password'] = bcrypt($request->password);
        $customer->password = $updateData['password'];

        if($customer->save()){
            return response([
                'message' => 'Update Customer Success',
                'data' => $customer
            ], 200);
        }

        return response([
            'message' => 'Update Customer Failed',
            'data' => null
        ], 400);
    }


}