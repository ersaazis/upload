<?php namespace App\Http\Controllers;

use ersaazis\cb\controllers\CBController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class AdminPhotobookAdminController extends CBController {


    public function cbInit()
    {
        $this->setTable("project_layout");
        $this->setPermalink("photobook_admin");
        $this->setPageTitle("Photobook Admin");

		$this->addText("Kode Transaksi","kode_transaksi")->strLimit(150)->maxLength(255);
		$this->addSelectTable("User","users_id",["table"=>"users","value_option"=>"id","display_option"=>"name","sql_condition"=>""]);
		$this->addText("Nomor Resi","no_resi")->required(false)->strLimit(150)->maxLength(255);
        $this->addSelectTable("Jenis Photobook","jenis_photobook_id",["table"=>"jenis_photobook","value_option"=>"id","display_option"=>"nama","sql_condition"=>""])->filterable(true);
        $this->addSelectTable("Tema Photobook","tema_photobook_id",["table"=>"tema_photobook","value_option"=>"id","display_option"=>"nama","sql_condition"=>""])->showAdd(false)->showEdit(false)->filterable(true);

        $this->addImage("Foto Cover","foto_cover")->showAdd(false)->showEdit(false)->required(false)->encrypt(true);
		$this->addText("Text Cover","text_cover")->showAdd(false)->showEdit(false)->required(false)->strLimit(150)->maxLength(255);

        $this->addFile("Hasil Desain Cover","hasil_cover")->required(false)->encrypt(true);
		$this->addFile("Hasil Desain Photobook","hasil_layout")->required(false)->encrypt(true);
		$this->addDatetime("Created At","created_at")->required(false)->showAdd(false)->showEdit(false)->showIndex(false);
		$this->addDatetime("Updated At","updated_at")->required(false)->showAdd(false)->showEdit(false)->showIndex(false);
		$this->addSelectOption("Status","status")->options(['Upload Foto'=>'Upload Foto','Proses Desain'=>'Proses Desain','Proses Produksi'=>'Proses Produksi','Selesai'=>'Selesai'])->filterable(true);
        
        $this->addActionButton("", function($row) {
		    return cb()->getAdminUrl('/photobook_admin/download/'.$row->primary_key);
        }, function($row) {
            if($row->status != 'Upload Foto')
                return true;
        }, "fa fa-download", 'success');

        $this->addSubModule("Lihat Foto", AdminLihatFotoController::class, "project_layout_id", function ($row) {
            $user = DB::table('users')->find($row->users_id);
            return [
              "Nama Pembeli"=> $user->name,
              "Email Pembeli"=> $user->email,
              "Kode Transaksi"=> '#'.$row->kode_transaksi,
            ];
        },function ($row){
            if($row->status != 'Upload Foto')
                return true;
        }, "fa fa-image", 'danger', true);

    }
    public function getDelete($id)
    {
        if(!module()->canDelete()) return cb()->redirect(cb()->getAdminUrl(),cbLang("you_dont_have_privilege_to_this_area"));
        $softDelete = true;

        $photobook=DB::table('project_layout')->find($id);
        if($photobook->foto_cover)
            Storage::delete([$photobook->foto_cover]);
        $photo=DB::table('upload')->where('project_layout_id',$photobook->id)->get();
        foreach($photo as $item){
            DB::table('upload')
                ->where(getPrimaryKey('upload'), $item->id)
                ->delete();
            Storage::delete([$item->foto]);
        }

        if ($softDelete === true && Schema::hasColumn('project_layout','deleted_at')) {
            DB::table('project_layout')
                ->where(getPrimaryKey('project_layout'), $id)
                ->update(['deleted_at' => date('Y-m-d H:i:s')]);
        } else {
            DB::table('project_layout')
                ->where(getPrimaryKey('project_layout'), $id)
                ->delete();
        }


        return cb()->redirectBack( cbLang("the_data_has_been_deleted"), 'success');
    }
    public function download($id){
        $foto=DB::table('upload')->where('project_layout_id',$id)->get();
        $zipname = $id.'.zip';
        $zip = new ZipArchive;
        $zip->open($zipname, ZipArchive::CREATE);
        foreach($foto as $item){
            $file=explode('/',$item->foto);
            if($item->urutan != "")
                $fileName=$item->urutan."_".$file[count($file)-1];
            else
                $fileName=$file[count($file)-1];

            if($item->text_custom != "")
                $zip->addFromString($fileName.'.txt', $item->text_custom);
            $zip->addFile($item->foto,$fileName);
        }
        $zip->close();
        ignore_user_abort(true);

        header('Content-Type: application/zip');
        header('Content-disposition: attachment; filename='.$zipname);
        header('Content-Length: ' . filesize($zipname));
        readfile($zipname);
        if(file_exists(public_path($zipname))){
            unlink(public_path($zipname));
        }
    }
}
