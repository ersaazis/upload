<?php

namespace App\Http\Controllers;

use ersaazis\cb\controllers\CBController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PilihTemaController extends CBController
{
    public function saveTema($photobook_id,$tema_photobook_id){
        $data['photobook'] = DB::table('project_layout')->find($photobook_id);
        if($data['photobook']->users_id != cb()->session()->id())
            return (cb()->redirect(cb()->getAdminUrl('photobook'),"Anda tidak memiliki akses untuk ini !", "warning"));

            DB::table('project_layout')->where(['id'=>$photobook_id])->update(['tema_photobook_id'=>$tema_photobook_id]);
        return cb()->redirect(action("AdminPhotobookController@getIndex"), "Tema Berhasil Disimpan !", "success");
    }
    public function pilihTema($photobook_id,$kategori_tema_id){
        $data = [];
        $data['kategori_tema'] = DB::table('kategori_tema')->get();
        $data['photobook'] = DB::table('project_layout')->find($photobook_id);
        if($data['photobook']->users_id != cb()->session()->id())
            return (cb()->redirect(cb()->getAdminUrl('photobook'),"Anda tidak memiliki akses untuk ini !", "warning"));
        $data['jenis_photobook'] = DB::table('jenis_photobook')->find($data['photobook']->jenis_photobook_id);
        $data['pageIcon'] = "fa fa-window-maximize";
        $data['page_title'] = "Pilih Tema";

        if($kategori_tema_id != 0){
            $data['kategori_tema_dipilih'] ="Tema ".DB::table('kategori_tema')->find($kategori_tema_id)->nama;
            $data['tema_photobook'] =DB::table('tema_photobook')->where(['kategori_tema_id'=>$kategori_tema_id,'jenis_photobook_id'=>$data['photobook']->jenis_photobook_id])->get();
        }
        else {
            $data['kategori_tema_dipilih'] ="Semua Jenis Tema";
            $data['tema_photobook'] =[];
        }

        return view("pilihtema", $data);
    }
}
