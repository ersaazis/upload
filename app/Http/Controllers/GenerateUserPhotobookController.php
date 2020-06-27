<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GenerateUserPhotobookController extends Controller
{
    public function getIndex(){
        $data['jenis_photobook']=DB::table('jenis_photobook')->get();
        $data['pageIcon'] = "fa fa-book";
        $data['page_title'] = "Tambah Pesanan Photobook";
        return view("photobook", $data);
    }
    public function postSave(Request $request)
    {
        $data=$request->all();
        $usersId=DB::table('users')->insertGetId([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'cb_roles_id' => '2',
        ]);
    
        DB::table('project_layout')->insert([
            'jenis_photobook_id'=>$data['jenis_photobook_id'],
            'users_id'=>$usersId,
            'status'=>'Upload Foto',
            'kode_transaksi'=>'#',
        ]);
        return cb()->redirectBack( 'Berhasil Di Buat', 'success');
    }
}
