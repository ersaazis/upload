<?php namespace App\Http\Controllers;

use ersaazis\cb\controllers\CBController;

class AdminTemaPhotobookController extends CBController {


    public function cbInit()
    {
        $this->setTable("tema_photobook");
        $this->setPermalink("tema_photobook");
        $this->setPageTitle("Tema Photobook");

        $this->addSelectTable("Jenis Photobook","jenis_photobook_id",["table"=>"jenis_photobook","value_option"=>"id","display_option"=>"nama","sql_condition"=>""]);
		$this->addText("Nama","nama")->strLimit(150)->maxLength(255);
		$this->addText("Fliphtml5 Url","fliphtml5_url")->strLimit(150)->maxLength(255);
		$this->addDatetime("Created At","created_at")->required(false)->showIndex(false)->showAdd(false)->showEdit(false);
		$this->addDatetime("Updated At","updated_at")->required(false)->showIndex(false)->showAdd(false)->showEdit(false);
        $this->addSelectTable("Kategori Tema","kategori_tema_id",["table"=>"kategori_tema","value_option"=>"id","display_option"=>"nama","sql_condition"=>""]);  
    }
}
