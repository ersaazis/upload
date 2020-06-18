@extends('crud::themes.adminlte.layout.layout')
@section('content')
<p>
    <a href="<?php echo cb()->getAdminUrl('/photobook/'); ?>"><< Kembali Ke Photobook</a>
</p>
<div class="box">
    <div class="box-header">
        <h1 class="box-title">{{$jenis_photobook->nama}} <small>( {{$kategori_tema_dipilih}} )</small></h1>
    </div>
    <div class="box-body">
        <div>
            <!-- <a class="btn btn-default" href="<?php echo cb()->getAdminUrl('/pilih_tema/'.$photobook->id.'/0'); ?>" role="button">Semua Tema</a> -->
            @foreach ($kategori_tema as $item)
                <a class="btn btn-default" href="<?php echo cb()->getAdminUrl('/pilih_tema/'.$photobook->id.'/'.$item->id); ?>" role="button">Tema {{$item->nama}}</a>    
            @endforeach
        </div>
    </div>
</div>
<div class="row">
    @foreach ($tema_photobook as $item)
        <div class="col-md-6">
            <div class="box">
                <div class="box-header">
                    <span class="box-title">{{$item->nama}}</span>
                </div>
                <div class="box-body">
                    <iframe allowfullscreen="" width="100%" height="400" class="embed-responsive-item iframe-custom" data-src="{{$item->fliphtml5_url}}" src="{{$item->fliphtml5_url}}"></iframe>
                </div>
                <div class="box-footer">
                    <a class="btn btn-primary btn-block" href="<?php echo cb()->getAdminUrl('/pilih_tema/save/'.$photobook->id.'/'.$item->id); ?>" role="button">Pilih Tema</a>    
                </div>
            </div>
        </div>    
    @endforeach
</div>
@endsection