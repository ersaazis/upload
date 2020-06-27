@extends('crud::themes.adminlte.layout.layout')
@section('content')
<div class="box box-default">
    <div class="box-header">
        <h1 class="box-title"><i class="fa fa-user"></i> Pembeli</h1>
    </div>
    <form method="post" action="<?php echo cb()->getAdminUrl('/generate/member/photobook') ?>">
        @csrf
    <div class="box-body">
        <div class="form-group">
            <label for="">Name</label>
            <input required="" type="text" placeholder="E.g : John Doe" name="name" class="form-control">
        </div>
        <div class="form-group">
            <label for="">E-mail</label>
            <input required="" type="email" placeholder="E.g : john@email.com" name="email" class="form-control">
        </div>
        <div class="form-group">
            <label for="">Password</label>
            <input required="" type="password" placeholder="Enter a new password" name="password" class="form-control">
        </div>
    </div>
</div>

<div class="box box-default">
    <div class="box-header with-border">
        <h1 class="box-title"><i class="fa fa-file"></i> Photobook</h1>
    </div>
    <div class="box-body" id="parent-form-area">
        <div class="form-group " id="form-group-jenis_photobook_id">
            <label> Jenis Photobook
                <span class="text-danger" title="crud.this_field_is_required">*</span>
            </label>
            <div class="row">
            <div class="col-sm-12">
                <select style="width: 100%" id="select-jenis_photobook_id" class="form-control select2 select2-hidden-accessible" required="" name="jenis_photobook_id" tabindex="-1" aria-hidden="true">
                    @foreach ($jenis_photobook as $item)
                        <option value="{{$item->id}}">{{$item->nama}}</option>
                    @endforeach
                </select>
            </div>
            </div>
        </div>
        <div class="box-footer">
            <input type="submit" class="btn btn-success" value="Simpan">
        </div>        
    </form>
</div>
@endsection