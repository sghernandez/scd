jQuery(function($){
      $.datepicker.regional['es'] = {
            closeText: 'Cerrar',
            prevText: '&#x3c;Ant',
            nextText: 'Sig&#x3e;',
            currentText: 'Hoy',
            monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
            'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
            monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
            'Jul','Ago','Sep','Oct','Nov','Dic'],
            dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
            dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
            dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
            weekHeader: 'Sm',
            dateFormat: 'yy-mm-dd',
            firstDay: 0,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''};
      $.datepicker.setDefaults($.datepicker.regional['es']);
}); 

$(function(){ calendario(); });	

function calendario(){
  	
	  var years, fecha, range;	
	   
	  fecha = new Date();
	  years = 1903 + fecha.getYear();   
	  range = 2000 + ':' + years;	
	  
		$( '.fecha' ).datepicker({
			changeMonth: true, 
			changeYear: true
		});	  
	  
     $( '.fecha' ).datepicker( "option", "yearRange", range);	
	
}

function search()
{  
   $('#form_search .input-search').each(function()
   {
      var data_column = $(this).attr('data-column');
      var input_val = $(this).val();

      dtable_serverSide.columns(data_column).search(input_val);
   });  

   dtable_serverSide.draw() 

}

/* carga la ruta enviada; si "form" está definida envia mediante
 * Post el contenido del formulario: form */
function carga_modal(ruta)
{           
    var loader = '<img src="' + base_url + 'img/tenor.gif' + '" width="90%" height="90%" />';

    $('#modal_form').modal({
        backdrop: 'static',
        keyboard: false
    });

    $('#modal_form .modal-title').html('Cargando...');
    $('#contenido_modal').html(loader); 
    $('#modal_form').modal('show'); 
      
	$.ajax({
        cache: false,
        url: ruta, 
        type: 'GET', 
	    dataType: 'JSON',
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        success: function (response) {                       
            $('#modal_form .modal-title').html(response.title); 
		    $('#contenido_modal').html(response.view); 	
            calendario();		 	    
        }        
      });    
}

/* valida_formulario - valida el formulario del lado del cliente 
 * usando: jqueryvalidate
 * 
 * formulario: id del formulario 
 * */
function valida_formulario(formulario)
{        
    $(document).ready(function() {
       
        //================================================================================
        // Para que valide los patterns del formulario
        $.validator.methods.pattern = function(value, element) {
            return (this.optional(element) || new RegExp(element.pattern).test(value));
        };
        // $.validator.messages.pattern = "Invalid input entered."; => asi se define en el footer si el idioma seleccionado es: 'en'     
        //================================================================================
    
        $("#" + formulario + " .error" ).html('');    
        $("#" + formulario).validate({
			
            debug:true,
            errorPlacement: function(error, element) 
            {
                var name = element.attr('name');
                var errorSelector = '.error[id="error_' + name + '"]';
                var $element = $(errorSelector);
                if ($element.length) { 
                    $(errorSelector).html(error.html());
                } else {
                    error.insertAfter(element);
                }
            },
           highlight: function(input) {
              $(input).addClass('error_input');
           },
           errorPlacement: function(error, element){},				
           submitHandler:function(){ valida_formulario_ajax(formulario); }
        });
    
    });     
}	

/*
 * VALIDA_FORMULARIO_AJAX: 
 * envia un formulario mediante Post;
 * si este no es válido estará entregando
 * las validaciones hechas del lado del servidor.
 * 
 * Si es válido entregará el resultado de la consulta
 * o acción, sea exitosa o fallida.
 */
function valida_formulario_ajax(formulario)
{ 
  var $form = $('#' + formulario); 
   
  $.ajax({
    url: $form.attr('action'), 
    type: 'POST',
    data: new FormData($form[0]),   
   // dataType: 'json',
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    processData: false,
    contentType: false,
    cache: false,
    async: true,  
    beforeSend: function()
    {
        $('#' + formulario).find(':input:not(:enabled)').each(function(){
            $(this).addClass( "deshabilitado");
        })
              
        $('#' + formulario).find(':input:not(:disabled)').attr('disabled', 'disabled');
    },      
    success: function(result) 
    {         
        var data = JSON.parse(result);  
		for(key in data){ $('#error_'+ key).html(data[key]); } 

        if(! data.errors)
        {  
            $('#' + formulario + ' .error').html('');
			$('#contenido_modal').html('<div class="alert alert-success" align="center" style="margin: 30px 40px">'+ data.success + '</div>'); 
			setTimeout(function() 
			{
				$('#modal_form').modal('hide'); 				  			   
				dtable_serverSide.ajax.reload(null, false);
			}, 1500);			   		                          
        }

        $('#' + formulario).find("*").removeAttr('disabled'); // habilita el formulario
        $('#' + formulario + ' .deshabilitado').attr('disabled', 'disabled').removeClass('deshabilitado');        
    } 
    
  });     	   
}
