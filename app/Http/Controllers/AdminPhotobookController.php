<?php namespace App\Http\Controllers;

use ersaazis\cb\controllers\CBController;
use ersaazis\cb\controllers\traits\Query;
use ersaazis\cb\controllers\partials\ButtonColor;
use ersaazis\cb\models\AddActionButtonModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminPhotobookController extends CBController {
    use Query;
    private $data;
    public function cbInit()
    {
        $this->setButtonEdit(false);
        $this->setButtonDetail(false);
        $this->data['table']='project_layout';
        $this->data['page_title']='Photobook';
        $this->data['permalink']='photobook';
        $this->setTable($this->data['table']);
        $this->setPermalink($this->data['permalink']);
        $this->setPageTitle($this->data['page_title']);
        $this->setBeforeIndexTable('
            <div class="box">
                <div class="box-header">
                    <div class="box-title"><i class="fa fa-info"></i> Keterangan</div>
                </div>
                <div class="box-body">
                    <table class="table">
                        <tr>
                            <td><button class="btn btn-xs btn-primary">Tombol Biru Tua</button></td>
                            <td>: Boleh diisi atau tidak</td>
                        </tr>
                        <tr>
                            <td><button class="btn btn-xs btn-info">Tombol Biru Muda</button></td>
                            <td>: Dapat diubah</td>
                        </tr>
                        <tr>
                            <td><button class="btn btn-xs btn-danger">Tombol Merah</button></td>
                            <td>: Wajib diisi</td>
                        </tr>
                    </table>
                </div>
            </div>
        ');
        $this->addText("Informasi",'id')->indexDisplayTransform(function($row) {
            $row=DB::table('project_layout')->find($row);
            // print_r($row);
            $jenis_photobook=DB::table('jenis_photobook')->find($row->jenis_photobook_id);
            $return='<div>Kode Transaksi : <b>#'.$row->kode_transaksi.'</b></div>';
            $return.='<div>No Resi : <b>'.$row->no_resi.'</b></div>';
            $return.='<div>Jenis : <b>'.$jenis_photobook->nama.'</b></div>';
            if(!empty($row->tema_photobook_id)){
                $tema_photobook=DB::table('tema_photobook')->find($row->tema_photobook_id);
                $return.='<div>Tema : <b>'.$tema_photobook->nama.'</b></div>';
                $return.='<div>Status : <b>'.$row->status.'</b></div>';
            }
            $return.='<div>Max Foto : <b>'.$jenis_photobook->jumlah_foto.'</b></div>';
            $jumUploadFoto=DB::table('upload')->where('project_layout_id',$row->id)->count();
            $return.='<div>Foto Diupload : <b>'.$jumUploadFoto.'</b></div>';
        return $return;
        })->columnWidth('auto')->showEdit(false)->showDetail(false);

        #---------------------------------------------------------------#
        
        $this->addActionButton("Detail", function($row) {
		    return cb()->getAdminUrl('/photobook/detail/'.$row->id);
        }, function($row) {
		    return true;
        }, "fa fa-eye", 'success btn-block');

        #---------------------------------------------------------------#

        $this->addActionButton("Pilih Tema", function($row) {
		    return cb()->getAdminUrl('/pilih_tema/'.$row->id."/0");
        }, function($row) {
            if($row->status != 'Upload Foto')
                return false;

		    return $row->tema_photobook_id == null;
        }, "fa fa-window-maximize", 'danger btn-block');

        $this->addActionButton("Edit Tema", function($row) {
		    return cb()->getAdminUrl('/pilih_tema/'.$row->id."/0");
        }, function($row) {
            if($row->status != 'Upload Foto')
                return false;

		    return $row->tema_photobook_id != null;
        }, "fa fa-window-maximize", 'info btn-block');

        #---------------------------------------------------------------#

        $this->addActionButton("Edit Foto Cover", function($row) {
		    return cb()->getAdminUrl('/photobook/edit/'.$row->id);
        }, function($row) {
            if($row->status != 'Upload Foto')
                return false;

		    return ($row->tema_photobook_id != null && $row->foto_cover != null);
        }, "fa fa-image", 'info btn-block');

        $this->addActionButton("Foto Cover", function($row) {
		    return cb()->getAdminUrl('/photobook/edit/'.$row->id);
        }, function($row) {
            if($row->status != 'Upload Foto')
                return false;

		    return ($row->tema_photobook_id != null && $row->foto_cover == null);
        }, "fa fa-image", 'primary btn-block');

        #---------------------------------------------------------------#

        $this->addActionButton("Edit Text Cover", function($row) {
		    return cb()->getAdminUrl('/photobook/edit/'.$row->id);
        }, function($row) {
            if($row->status != 'Upload Foto')
                return false;

		    return ($row->tema_photobook_id != null && $row->text_cover != null);
        }, "fa fa-font", 'info btn-block');

        $this->addActionButton("Text Cover", function($row) {
		    return cb()->getAdminUrl('/photobook/edit/'.$row->id);
        }, function($row) {
            if($row->status != 'Upload Foto')
                return false;

		    return ($row->tema_photobook_id != null && $row->text_cover == null);
        }, "fa fa-font", 'primary btn-block');

        #---------------------------------------------------------------#

        $this->addSubModule("Upload Foto", AdminUploadFotoController::class, "project_layout_id", function ($row) {
            $user = DB::table('users')->find($row->users_id);
            return [
              "Nama Pembeli"=> $user->name,
              "Email Pembeli"=> $user->email,
              "Kode Transaksi"=> '#'.$row->kode_transaksi,
            ];
        },function ($row){
            $jenis_photobook=DB::table('jenis_photobook')->find($row->jenis_photobook_id);
            $jumUploadFoto=DB::table('upload')->where('project_layout_id',$row->id)->count();
            if($row->status != 'Upload Foto')
                return false;
            if($jenis_photobook->jumlah_foto > $jumUploadFoto)
                return $row->tema_photobook_id != null;
            else return false;
        }, "fa fa-upload", 'danger btn-block', true);

        $this->addSubModule("Lihat Foto", AdminUploadFotoController::class, "project_layout_id", function ($row) {
            $user = DB::table('users')->find($row->users_id);
            return [
              "Nama Pembeli"=> $user->name,
              "Email Pembeli"=> $user->email,
              "Kode Transaksi"=> '#'.$row->kode_transaksi,
            ];
        },function ($row){
            $jenis_photobook=DB::table('jenis_photobook')->find($row->jenis_photobook_id);
            $jumUploadFoto=DB::table('upload')->where('project_layout_id',$row->id)->count();
            if($row->status != 'Upload Foto')
                return false;
            if($jenis_photobook->jumlah_foto <= $jumUploadFoto)
                return $row->tema_photobook_id != null;
            else return false;
        }, "fa fa-upload", 'info btn-block', true);

        #---------------------------------------------------------------#

        $this->addActionButton("Selesai", function($row) {
		    return cb()->getAdminUrl('/photobook/save/'.$row->id);
        }, function($row) {
            if($row->status != 'Upload Foto' or $row->tema_photobook_id == null)
                return false;
            else return true;
        }, "fa fa-check", 'success btn-block',true);

        #---------------------------------------------------------------#

        $this->addSelectTable("Jenis Photobook","jenis_photobook_id",["table"=>"jenis_photobook","value_option"=>"id","display_option"=>"nama","sql_condition"=>""])->showAdd(false)->showEdit(false)->showIndex(false);
		$this->addText("Kode Transaksi","kode_transaksi")->showIndex(false)->showEdit(false)->strLimit(150)->maxLength(255);
        $this->addSelectTable("Tema Photobook","tema_photobook_id",["table"=>"tema_photobook","value_option"=>"id","display_option"=>"nama","sql_condition"=>""])->showAdd(false)->showEdit(false)->showIndex(false);

        $this->addImage("Foto Cover","foto_cover")->required(false)->encrypt(true)->showIndex(false);
		$this->addText("Text Cover","text_cover")->required(false)->showIndex(false)->strLimit(150)->maxLength(255);

        $this->addSelectTable("User","users_id",["table"=>"users","value_option"=>"id","display_option"=>"name","sql_condition"=>""])->showAdd(false)->showEdit(false)->showIndex(false);
		$this->addDatetime("Created At","created_at")->required(false)->showIndex(false)->showAdd(false)->showEdit(false);
		$this->addDatetime("Updated At","updated_at")->required(false)->showIndex(false)->showAdd(false)->showEdit(false);
		$this->addText("Status","status")->showAdd(false)->showIndex(false)->showEdit(false);
        
        $this->hookBeforeUpdate(function($data, $id) {
            // Todo: code here
            $photobook=DB::table($this->data['table'])->find($id);
            if($photobook->users_id != cb()->session()->id())
                return [];
            unset($data['kode_transaksi']);
            unset($data['hasil_cover']);
            unset($data['hasil_layout']);
            unset($data['jenis_photobook_id']);
            unset($data['tema_photobook_id']);
            unset($data['users_id']);
            unset($data['status']);
            // Don't forget to return back
            return $data;
        });

        $this->hookIndexQuery(function($query) {
            $query->where("users_id", cb()->session()->id());
            return $query;
        });
    }
    public function save($id){
        $cek=DB::table($this->data['table'])->find($id);
        if($cek->users_id != cb()->session()->id())
            return (cb()->redirect(cb()->getAdminUrl($this->data['permalink']),"Anda tidak memiliki akses untuk ini !", "warning"));
        DB::table($this->data['table'])->where('id',$id)->update(['status'=>'Proses Desain']);
        return (cb()->redirect(cb()->getAdminUrl($this->data['permalink']),"Terimakasih Kami Akan Segera Memproses Pesanan Anda !", "success"));
    }
    public function getDetail($id)
    {
        if(!module()->canRead()) return cb()->redirect(cb()->getAdminUrl(),cbLang("you_dont_have_privilege_to_this_area"));
        $data = [];
        $data['row'] = $this->repository()->where($this->data['table'].'.'.cb()->findPrimaryKey($this->data['table']), $id)->first();
        if($data['row']->users_id != cb()->session()->id())
            return (cb()->redirect(cb()->getAdminUrl($this->data['permalink']),"Anda tidak memiliki akses untuk ini !", "warning"));
        $data['page_title'] = $this->data['page_title'].' : '.cbLang('detail');
        return view('crud::module.form.form_detail', array_merge($data, $this->data));
    }
    public function getEdit($id)
    {
        if(!module()->canUpdate()) return cb()->redirect(cb()->getAdminUrl(),cbLang("you_dont_have_privilege_to_this_area"));

        $data = [];
        $data['row'] = $this->repository()->where($this->data['table'].'.'.getPrimaryKey($this->data['table']), $id)->first();
        if($data['row']->users_id != cb()->session()->id())
            return (cb()->redirect(cb()->getAdminUrl($this->data['permalink']),"Anda tidak memiliki akses untuk ini !", "warning"));
        $data['page_title'] = $this->data['page_title'].' : '.cbLang('edit');
        $data['action_url'] = module()->editSaveURL($id);
        return view('crud::module.form.form', array_merge($data, $this->data));
    }
}
