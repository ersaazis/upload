<?php namespace App\Http\Controllers;

use ersaazis\cb\controllers\CBController;

class AdminLihatFotoController extends CBController {


    public function cbInit()
    {
        $this->setTable("upload");
        $this->setPermalink("lihat_foto");
        $this->setPageTitle("Lihat Foto");
        $this->setButtonAdd(false);
        $this->setButtonDelete(false);
        $this->setButtonEdit(false);

        $this->addSelectTable("Photobook","project_layout_id",["table"=>"project_layout","value_option"=>"id","display_option"=>"kode_transaksi","sql_condition"=>""])->showIndex(false)->showAdd(false)->showEdit(false);
		$this->addText("Urutan","urutan")->showAdd(false)->showEdit(false)->strLimit(150)->maxLength(255);
		$this->addImage("Foto","foto")->encrypt(true);
		$this->addDatetime("Created At","created_at")->required(false)->showIndex(false)->showAdd(false)->showEdit(false);
		$this->addDatetime("Updated At","updated_at")->required(false)->showIndex(false)->showAdd(false)->showEdit(false);

    }
}
