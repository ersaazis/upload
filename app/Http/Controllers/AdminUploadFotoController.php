<?php namespace App\Http\Controllers;

use ersaazis\cb\controllers\CBController;
use ersaazis\cb\controllers\traits\Query;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminUploadFotoController extends CBController {
    use Query;

    private $data;
    public function cbInit()
    {
        $this->data['table']='upload';
        $this->data['page_title']='Upload Foto';
        $this->data['permalink']='upload_foto';
        $this->setTable($this->data['table']);
        $this->setPermalink($this->data['permalink']);
        $this->setPageTitle($this->data['page_title']);

        $this->addSelectTable("Kode Transaksi Photobook","project_layout_id",["table"=>"project_layout","value_option"=>"id","display_option"=>"kode_transaksi","sql_condition"=>""])->showIndex(false);
		$this->addImage("Foto","foto")->encrypt(true);
        $this->addNumber("Urutan","urutan")->required(false);
        $this->addText("Text Tambahan","text_custom")->required(false)->placeholder('Contoh : Hari Yang Indah')->help(' Boleh Dikosongkan');
		$this->addDatetime("Created At","created_at")->required(false)->showAdd(false)->showIndex(false)->showEdit(false);
        $this->addDatetime("Updated At","updated_at")->required(false)->showAdd(false)->showIndex(false)->showEdit(false);
        
        $this->hookBeforeUpdate(function($data,$id) {
            $photobook=DB::table($this->data['table'])->find($id);
            if($photobook->users_id != cb()->session()->id())
                return [];

            $data['users_id']=cb()->session()->id();
            $upload=DB::table('upload')->find($id);
            if($data['foto'] != $upload->foto)
                Storage::delete([$upload->foto]);
            return $data;
        });
        $this->hookIndexQuery(function($query) {
            $query->where("upload.users_id", cb()->session()->id());
            return $query;
        });
    }
    private function maxUpload(){
        $subModule=Cache::get("subModule".Request::input('sub_module'));
        if(!$subModule){
            return (cb()->redirect(cb()->getAdminUrl('photobook'),"Anda tidak memiliki akses untuk ini !", "warning"));
        }
        $photobook=DB::table('project_layout')->find($subModule['foreignValue']);
        $jenis_photobook=DB::table('jenis_photobook')->find($photobook->jenis_photobook_id);
        $jumUploadFoto=DB::table('upload')->where('project_layout_id',$subModule['foreignValue'])->count();
        if($jenis_photobook->jumlah_foto <= $jumUploadFoto){
            return (cb()->redirect(cb()->getAdminUrl('photobook'),"Foto Yang Di Upload Telah Maximal !", "warning"));
        }
    }
    public function getEdit($id)
    {
        if(!module()->canUpdate()) return cb()->redirect(cb()->getAdminUrl(),cbLang("you_dont_have_privilege_to_this_area"));
        $subModule=Cache::get("subModule".Request::input('sub_module'));
        if(!$subModule){
            return (cb()->redirect(cb()->getAdminUrl('photobook'),"Anda tidak memiliki akses untuk ini !", "warning"));
        }
        $data = [];
        $data['row'] = $this->repository()->where('upload'.'.'.getPrimaryKey('upload'), $id)->first();
        $data['page_title'] = $this->data['page_title'].' : '.cbLang('edit');
        $data['action_url'] = module()->editSaveURL($id);
        return view('crud::module.form.form', array_merge($data, $this->data));
    }

    public function getAdd()
    {
        if(!module()->canCreate()) return cb()->redirect(cb()->getAdminUrl(),cbLang("you_dont_have_privilege_to_this_area"));
        if($this->maxUpload())
            return $this->maxUpload();

        $data = [];
        $data['page_title'] = 'Upload Foto : '.cbLang('add');
        $data['action_url'] = module()->addSaveURL()."?sub_module=".Request::input('sub_module');
        return view('crud::module.form.form',array_merge($data));
    }
    public function postAddSave()
    {
        if(!module()->canCreate()) return cb()->redirect(cb()->getAdminUrl(),cbLang("you_dont_have_privilege_to_this_area"));
        if($this->maxUpload())
            return $this->maxUpload();

        try {
            $this->validation();
            columnSingleton()->valueAssignment();
            $data = columnSingleton()->getAssignmentData();

            //Clear data from Primary Key
            unset($data[ cb()->pk('upload') ]);

            if(Schema::hasColumn('upload', 'created_at')) {
                $data['created_at'] = date('Y-m-d H:i:s');
            }

            if(Schema::hasColumn('upload', 'updated_at')) {
                $data['updated_at'] = date('Y-m-d H:i:s');
            }
            $data['users_id']=cb()->session()->id();
            $id = DB::table('upload')->insertGetId($data);

        } catch (CBValidationException $e) {
            Log::debug($e);
            return cb()->redirectBack($e->getMessage(),'info');
        } catch (\Exception $e) {
            Log::error($e);
            return cb()->redirectBack(cbLang("something_went_wrong"),'warning');
        }

        if (Str::contains(request("submit"),cbLang("more"))) {
            return cb()->redirectBack(cbLang("the_data_has_been_added"), 'success');
        } else {
            if(verifyReferalUrl()) {
                return cb()->redirect(getReferalUrl("url"), cbLang("the_data_has_been_added"), 'success');
            } else {
                return cb()->redirect(module()->url(), cbLang("the_data_has_been_added"), 'success');
            }
        }
    }
    public function getDelete($id)
    {
        if(!module()->canDelete()) return cb()->redirect(cb()->getAdminUrl(),cbLang("you_dont_have_privilege_to_this_area"));
        $softDelete = true;
        $subModule=Cache::get("subModule".Request::input('sub_module'));
        if(!$subModule){
            return (cb()->redirect(cb()->getAdminUrl('photobook'),"Anda tidak memiliki akses untuk ini !", "warning"));
        }

        $upload=DB::table('upload')->find($id);
        Storage::delete([$upload->foto]);

        if ($softDelete === true && Schema::hasColumn('upload','deleted_at')) {
            DB::table('upload')
                ->where(getPrimaryKey('upload'), $id)
                ->update(['deleted_at' => date('Y-m-d H:i:s')]);
        } else {
            DB::table('upload')
                ->where(getPrimaryKey('upload'), $id)
                ->delete();
        }
        return cb()->redirectBack( cbLang("the_data_has_been_deleted"), 'success');
    }

}
