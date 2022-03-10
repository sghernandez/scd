@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="text-center">
            <h3>Listado de Diezmos</h3>
        </div>      
    </div>
</div><br>

<div align="center" id="form_search">
     <span onclick="carga_modal('{{ route('diezmos.create') }}')" class="btn btn-primary"><i class="fa fa-plus"></i></span>
     <a href="{{ route('diezmos') }}" class="btn btn-info"><i class="fa fa-refresh"></i></a>

      {!! Form::text('fecha_1', null, array('data-column' => 1, 'placeholder' => 'Desde', 'class' => 'form-control input-search fecha col-sm-2', 'readonly' => 'readonly', 'style' => 'display: inline')) !!}  
      {!! Form::text('fecha_2', null, array('data-column' => 2, 'placeholder' => 'Hasta', 'class' => 'form-control input-search fecha col-sm-2', 'readonly' => 'readonly', 'style' => 'display: inline')) !!}  
      @can('super-admin') 
       {{ Form::select('user_id', \App\Util\Util::getUsers(), null, array('data-column' => 3, 'class' => 'form-control input-search col-sm-3', 'style' => 'display: inline')) }}                             
      @endcan
     <button type=button onclick="search()" class="btn btn-info"><i class="fa fa-search"></i></button>     
</div><br>  

<div class="container">
    <table id="dataTable" class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Nombre</th>
                <th>Documento</th>
                <th>Diezmo</th>
                <th>Ofrenda</th>
                <th>Entregado</th>     
                <th width="180px">Acciones</th>
             </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

@endsection

