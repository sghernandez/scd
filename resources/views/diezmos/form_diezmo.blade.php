   @if(isset($diezmo))
    {!! Form::model($diezmo, ['id' => 'form', 'method' => 'POST','route' => ['diezmos.update', $diezmo->id]]) !!}  
    <input type="hidden" name="id" value="{{ $diezmo->id }}" />
  @else
     {!! Form::open(array('id' => 'form', 'route' => 'diezmos.store','method'=>'POST')) !!}
  @endif
        <div class="row">          
  
            <?php if($super_admin): ?>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>Usuario:</strong>            
                        {!! Form::select('user_id', $users, (isset($diezmo) ? $diezmo->user_id : 0), array('class' => 'form-control', 'required' => 'required')) !!}                             
                        <div id="error_user_id" class="error"></div> 
                    </div>
                </div>  

            <?php else: ?>
            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}" />
            <?php endif ?>

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Fecha:</strong>
                {!! Form::text('fecha', null, array('minlegth' => 10, 'class' => 'form-control fecha', 'required' => 'required', 'readonly' => 'readonly')) !!}                
                <div id="error_fecha" class="error"></div> 
            </div>
        </div>            
        
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Diezmo:</strong>
                {!! Form::number('diezmo', null, array('class' => 'form-control', 'required' => 'required')) !!} 
                <div id="error_diezmo" class="error"></div>                
            </div>
        </div>    
        
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Ofrenda:</strong>
                {!! Form::number('ofrenda', null, array('class' => 'form-control', 'required' => 'required')) !!}      
                <div id="error_ofrenda" class="error"></div>          
            </div>
        </div>                  

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Entregado:</strong>            
                 {!! Form::select('entregado', [null => 'Seleccione una opción', 0 => 'No', 1 => 'Sí'], (isset($diezmo) ? $diezmo->entregado : null), array('class' => 'form-control', 'required' => 'required')) !!}                                              
                 <div id="error_fecha" class="error"></div> 
            </div>
        </div>    

        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Observaciones:</strong>
                {!! Form::text('observacion', null, array('class' => 'form-control')) !!}                
            </div>
        </div>          
        
        <div class="col-xs-12 col-sm-12 col-md-12">
            <button type="submit" onclick="return valida_formulario('form')" class="btn btn-primary pull-left"><i class="fa fa-floppy-o"></i> Guardar</button> 
            <button type="button" class="btn btn-warning pull-right" data-dismiss="modal">Cerrrar</button>                                   
        </div> <br>      

    </div>

    {!! Form::close() !!}
