<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\ModelWork;
use DateTime;
use App\User;
use Illuminate\Support\Facades\Input;
use JWTAuth;

class WorkOrder extends Controller
{
    public function buat (Request $req){
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
            'deskripsi' => 'string',
            'foto' => 'string',
            'keterangan' => 'string',
        ]);

        if($val->fails()){
            return Response()->json([$val->errors()]);
        }

        $penerima = User::join('profile_workorder', 'profile_workorder.np_penerima','=','tmpegawai.np')
                        ->get()->where('np', $req->np_penerima)->first();

        $buat = ModelWork::create([
            'id' => Rand(1000,9000),
            'np_penerima' => $req->np_penerima,
            'np_pengirim' => $user->np,
            'tanggal' => date('m/d/y H:i:s a'),
            'deskripsi' => $req->deskripsi,
            'foto' => $req->foto,
            'keterangan' => $req->keterangan,
            'nama_penerima'=> $penerima->nama,
            'nama_pengirim'=> $user->nama,
        ]);

        if($buat){
            return Response()->json(['Pesan Terkirim', $buat]);
        } else {
            return Response()->json(['Pesan Gagal', 401]);
        }
    }

    public function tampil_kirim(){
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

        $kirim = ModelWork::where('np_pengirim', "=", $user->np)->get()->sortByDesc('tanggal');
        $count = Count($kirim);

        if($count > 0){
            foreach($kirim as $a){
                $tampil[] = array(
                    'ID Pesan'=>$a->id,
                    'Tanggal'=>$a->tanggal,
                    'Nama Penerima'=>$a->nama_penerima,
                    'Nama Pengirim'=>$a->nama_pengirim,
                    'Deskripsi'=>$a->deskripsi,
                    'Foto'=>$a->foto,
                    'Tanggal Dikerjakan'=>$a->tanggal_dikerjakan,
                    'Keterangan'=>$a->keterangan,
                );
            }
            return Response()->json(compact(['tampil']));
        } else {
            return Response()->json(['Buatlah Work Order']);
        }

    }

    public function tampil_terima(){
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

        $pesan = ModelWork::where('np_penerima',"=", $user->np)->get()->sortByDesc('tanggal');
        $count = Count($pesan);
        
        if($count > 0 ){
            foreach($pesan as $a){
                $tampil[] = array(
                    'ID Pesan'=>$a->id,
                    'Tanggal'=>$a->tanggal,
                    'Nama Penerima'=>$a->nama_penerima,
                    'Nama Pengirim'=>$a->nama_pengirim,
                    'Deskripsi'=>$a->deskripsi,
                    'Foto'=>$a->foto,
                    'Tanggal Dikerjakan'=>$a->tanggal_dikerjakan,
                    'Keterangan'=>$a->keterangan,
                );
            }
            return Response()->json(compact(['tampil']));
        } else {
            return Response()->json(['Tidak Ada Work Order']);
        }
    }

    public function selesai(Request $req, $id){
        $now = new DateTime();
        $this->validate($req,[
            'foto'=>'image|mimes:jpg,png,jpeg'
        ]);

        if($req->hasFile('foto')){
            $foto = $req->file('foto')->getClientOriginalName();
            $path = $req->file('foto')->storeAs('public/image', $foto);
        } else {
            $foto = 'No Image';
        }

        $update = ModelWork::where('id', $id)->update([
            'tanggal_dikerjakan'=>$now,
            'foto'=>$foto,
        ]);

        if($update){
            return Response()->json(['WORKORDER SELESAI']);
        } else {
            return Response()->json(['errors'], 401);
        }
    }

    public function delete($id){
        $hapus = where('id', $id)->delete();
        if($hapus){
            return Response()->json(['WorkOrder Berhasil Dihapus']);
        } else {
            return Response()->json(['errors', 401]);
        }
    }
}
