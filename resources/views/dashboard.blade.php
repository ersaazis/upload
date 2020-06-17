@extends('crud::themes.adminlte.layout.layout')
@section('content')
<div class="row"><div class="col-md-6 col-md-offset-3 col-sm-12"><img src="<?php echo url('img/logo.png'); ?>" width="100%"></div></div>
<div class="list-group">
    <span class="list-group-item">
        <h4>1 ) Pilih Menu Photobook</h4>
    </span>
    <span class="list-group-item">
        <h4>2 ) Pilih Tema <small>(wajib)</small></h4>
    </span>
    <span class="list-group-item">
        <h4 class="list-group-item-heading">3 ) Tambah Foto atau Text Cover <small>(optional)</small></h4>
        <p class="list-group-item-text"><br><img src="<?php echo url('img/cover.png'); ?>" style="width:100%;max-width:500px"></p>
    </span>
    <span class="list-group-item">
        <h4>4 ) Upload Foto <small>(wajib)</small></h4>
    </span>
    <span class="list-group-item">
        <h4>5 ) Selesai <small>(jika foto yang diupload selesai)</small></h4>
    </span>
    <a href="<?php echo cb()->getAdminUrl('photobook'); ?>" class="active list-group-item">
        <i class="fa fa-upload"></i> Upload Foto
    </a>
</div>
@endsection