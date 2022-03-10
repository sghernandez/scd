    @if(isset($user))
      {!! Form::model($user, ['id' => 'form', 'method' => 'POST', 'route' => ['users.update', $user->id]]) !!}  
      <input type="hidden" name="id" value="{{ $user->id }}" />
    @else
       {!! Form::open(array('id' => 'form', 'route' => 'users.store','method'=>'POST')) !!}
    @endif
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Nombre:</strong>
            {!! Form::text('name', null, array('placeholder' => 'Nombre','class' => 'form-control', 'required' => 'required')) !!}
            <div id="error_name" class="error"></div> 
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Apellidos:</strong>
            {!! Form::text('lastname', null, array('placeholder' => 'Apellidos','class' => 'form-control', 'required' => 'required')) !!}
            <div id="error_lastname" class="error"></div> 
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>No. Documento:</strong>
            {!! Form::number('document', null, array('placeholder' => 'No. Documento','class' => 'form-control', 'required')) !!}
            <div id="error_document" class="error"></div> 
        </div>
    </div>    
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Email:</strong>
            {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control', 'required')) !!}
            <div id="error_email" class="error"></div> 
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Password:</strong>
            {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control')) !!}
            <div id="error_password" class="error"></div> 
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Confirme Password:</strong>
            {!! Form::password('confirm-password', array('placeholder' => 'Confirmar Password','class' => 'form-control')) !!}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>Rol:</strong>            
             {!! Form::select('roles[]', $roles, (isset($userRole) ? $userRole : []), array('class' => 'form-control', 'multiple', 'required')) !!}          
             <div id="error_roles" class="error"></div> 
        </div>
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12">
        <button type="submit" onclick="return valida_formulario('form')" class="btn btn-primary pull-left"><i class="fa fa-floppy-o"></i> Guardar</button> 
        <button type="button" class="btn btn-warning pull-right" data-dismiss="modal">Cerrrar</button>                                   
    </div> <br> 

</div>
{!! Form::close() !!}
    </div>
   </div>  
  </div>

