@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="text-center">
            <h3>Listado de Usuarios</h3>
        </div>      
    </div>
</div><br>

<div align="center" id="form_search">
    <span onclick="carga_modal('{{ route('users.create') }}')" class="btn btn-primary"><i class="fa fa-plus"></i></span>
    <a href="{{ url('users') }}" class="btn btn-info"><i class="fa fa-refresh"></i></a>

     {!! Form::text('search', null, array('data-column' => 1, 'placeholder' => 'Buscar...', 'class' => 'form-control input-search col-sm-4', 'style' => 'display: inline')) !!}  
    <button type=button onclick="search()" class="btn btn-info"><i class="fa fa-search"></i></button>     
</div><br>  

 <div class="container">
     <table id="dataTable" class="table table-bordered">
         <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellidos</th>
            <th>Documento</th>
            <th>Email</th>
            <th>Roles</th>
            <th width="120px">
             Acciones
         </th>
         </thead>
         <tbody></tbody>
     </table>
 </div>

@endsection