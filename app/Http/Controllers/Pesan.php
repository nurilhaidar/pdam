<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ModelPesan;
use App\User;
use Validator;
use JWTAuth;

class Pesan extends Controller
{
    public function buat(Request $req){
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

        $val = Validator::make($req->all(),[
            'np_penerima' => 'required',
            'pesan' => 'required'
        ]);

        if($val->fails()){
            return Response()->json($val -> errors());
        }

        $pesan = ModelPesan::create([
            'id' => Rand(1000,9000),
            'waktu' => date('m/d/Y h:i:s a', time()),
            'pesan' => $req->pesan,
            'np_penerima' => $req->np_penerima,
            'np_pengirim' => $user->np,
        ]);
        
        if($pesan){
            return Response()->json(['Pesan Terkirim', $pesan]);
        } else {
            return Response()->json(['Pesan Gagal'], 401);
        }
    }

    public function edit(Request $req, $id){
        $val = Validator::make($req->all(),[
            'pesan' => 'required'
        ]);

        if($val->fails()){
            return Response()->json([$val->errors()]);
        }

        $edit = ModelPesan::where('id', $id)->update([
            'pesan'=>$req->pesan,
        ]);

        if($edit){
            return Response()->json(['PESAN DIEDIT']);
        } else {
            return Response()->json(['ERRORS'], 401);
        }
    }

    public function hapus($id){
        $hapus = ModelPesan::where('id', $id)->delete();
        if($hapus){
            return Response()->json(['PESAN TERHAPUS']);
        } else{
            return Response()->json(['ERRORS'], 401);
        }
    }

    public function tampil(){
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

        $tampil = ModelPesan::where('np_penerima', $user->np)->first();
        $data = ModelPesan::join('tmpegawai', 'tmpegawai.np', 'profile_pesan.np_pengirim')->get()
                            ->where('np_penerima', $user->np)->first();

    }
}
