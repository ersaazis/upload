<?php

namespace App\Http\Controllers;

use ersaazis\cb\controllers\CBController;
use Illuminate\Http\Request;

class DashboardController extends CBController
{
    public function getIndex(){
        $data['pageIcon'] = "fa fa-dashboard";
        $data['page_title'] = "Cara Mengupload Foto";
        return view("dashboard", $data);
    }
}
