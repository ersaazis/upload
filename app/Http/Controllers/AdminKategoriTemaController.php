<?php namespace App\Http\Controllers;

use ersaazis\cb\controllers\CBController;

class AdminKategoriTemaController extends CBController {


    public function cbInit()
    {
        $this->setTable("kategori_tema");
        $this->setPermalink("kategori_tema");
        $this->setPageTitle("Kategori Tema");

        $this->addText("Nama","nama")->strLimit(150)->maxLength(255);
		$this->addDatetime("Created At","created_at")->required(false)->showIndex(false)->showAdd(false)->showEdit(false);
		$this->addDatetime("Updated At","updated_at")->required(false)->showIndex(false)->showAdd(false)->showEdit(false);
		

    }
}
