<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;
use JWTAuth;
use App\ModelPegawai;
use Tymon\JWTAuth\Exceptions\JWTException;

class Pegawai extends Controller
{
    public function login(Request $req){
        $validator = Validator::make($req->all(),
            [
            'np'=>'required',
            'pswd'=>'required',
            ]
        );

        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $user =  User::where('np',$req->np)->first();

        if (!$userToken=JWTAuth::fromUser($user)) {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }

        if($req->pswd == '123'){
            return response()->json(['1', $userToken]);
        } else {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }
    }

    public function getAuthenticatedUser()
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());
            
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            
            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        $data = User::join('tmbagian', 'tmbagian.kdbagian', 'tmpegawai.kdbagian')
                        ->join('tmjabatan', 'tmjabatan.kdjabatan', 'tmpegawai.kdjabatan')
                        ->join('tmunit', 'tmunit.kdunit', 'tmpegawai.kdunit')
                        ->join('tmagama', 'tmagama.kdagama', 'tmpegawai.agama')
                        ->get()->where('np',$user->np)->first();

        $tampil = array(
            'No Pegawai'=>$user->np,
            'Nama'=>$user->nama,
            'Alamat'=>$user->alamat,
            'Jenis Kelamin'=>$user->jk,
            'Tanggal Lahir'=>$user->tgllahir,
            'Agama'=>$data->agama,
            'Jabatan'=>$data->jabatan,
            'Bagian'=>$data->bagian,
            'Unit'=>$data->unit,
        );

        return response()->json(compact('tampil'));
    }

    public function getAll(){
        $tampil = User::get();
        if($tampil){
            return Response()->json([$tampil]);
        } else {
            return Response()->json(['error']);
        }
    }
}