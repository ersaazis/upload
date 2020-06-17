<?php namespace App\Http\Controllers;

use ersaazis\cb\controllers\CBController;

class AdminJenisPhotobookController extends CBController {


    public function cbInit()
    {
        $this->setTable("jenis_photobook");
        $this->setPermalink("jenis_photobook");
        $this->setPageTitle("Jenis Photobook");

        $this->addText("Nama","nama")->strLimit(150)->maxLength(255);
		$this->addText("Ukuran","ukuran")->strLimit(150)->maxLength(255);
		$this->addNumber("Jumlah Foto","jumlah_foto");
		$this->addNumber("Jumlah Halaman","jumlah_halaman");
		$this->addDatetime("Created At","created_at")->required(false)->showAdd(false)->showEdit(false);
		$this->addDatetime("Updated At","updated_at")->required(false)->showAdd(false)->showEdit(false);
		

    }
}
