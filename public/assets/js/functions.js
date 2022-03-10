// $.fn.dataTable.ext.errMode = 'none'; 
setInterval(REinit, 700);

function REinit() 
{
  $(document).ready(function() 
  {
	  
   if ( ! $.fn.DataTable.isDataTable( '#dataTable' ) ) // De este modo lo inicializa solo cuando es necesario
   {
 	    dtable_serverSide = $('#dataTable').DataTable({ 
	
	        "processing": true, 
	        "serverSide": true, // Procesa los datos del lado del servidor
	        "order": [], // Orden inicial. Ej => "order": [[2, 'asc']],
	
	        // Carga el contenido de la tabla mediante Ajax
	        "ajax": {
	            "url": php_baseurl + $('[name="datatables_ruta"]').val(),
	            "type": "POST"
	        },
	        	
	        // Definir las propiedades de inicialización de las columnas
	        "columnDefs": [
		        { 
		            "targets": [$('[name="datatables_targets"]').val()], 
		            "orderable": false, // no ordenable	
		        },
	        ],
	        "displayLength": 10, // Cantidad de filas que muestra inicialmente
            "lengthMenu": [[10, 25, 50, 100, 200], [10, 25, 50, 100, 200]],
            "searchDelay": 300,        
            "dom": '<"H"lfr>t<"F"ip>',
			 "lengthChange": false,
		    language: {
		        processing:     "Procesando...",
		        search:         "Buscar:",
		        lengthMenu:    " _MENU_ ",
		        info:           "Mostrando _START_ a _END_ de _TOTAL_ registros",
		        infoEmpty:      "Mostrando 0 a 0 de 0 registros",
		        infoFiltered:   "(filtrados de _MAX_ en total)",
		        infoPostFix:    "",
		        loadingRecords: "Cargando...",
		        zeroRecords:    "No se hallaron registros",
		        emptyTable:     "No se hallaron registros",
		        paginate: {
		            first:      "Primera Pág",
		            previous:   "<i class='fa fa-backward'></i> Atrás",
		            next:       "Siguiente <i class='fa fa-forward'></i>",
		            last:       "Última pág."
		        }
		    }       
          	
	    });
    }
	
	// Incializar componentes cuando la aplicación carga las vistas por ajax
	/*
	$('.alguna_clase').each(function (i, obj) {	
	   if (! $(obj).hasClass("activo_alguna_clase")) {
		   obj.className += " activo_alguna_clase";
	       // alguna acción
		}
	}); */		
	
   });	// Finaliza document.ready
}	
 

   /*
      search: busca con datatables del lado del servidor
    */
   function search()
   {  
      $('#form_search .search').each(function(){
           var col = $(this).attr('id');
           dtable_serverSide.columns(col).search($('#form_search #' + col).val());
      });  
      
      dtable_serverSide.draw()  
   }
       
/* carga la ruta enviada; si "form" está definida envia mediante
 * Post el contenido del formulario: form */
function ruta(ruta, form)
{        
   var data_post;
   $('#content').html('<div align="center"><img src="'+ baseurl + 'assets/img/tenor.gif" alt="loading" style="margin-top: 100px"/></div>');
   if(valor_variable(form)){ data_post = $('#' + form).serialize(); }  
   
	$.ajax({
      cache: false,
      url: php_baseurl + ruta, 
      type: 'POST', 
	  data: data_post,
      success: function (response) {             
          $('#content').html(response);                              
      }        
   });    
}

/* valor_variable - si la variable no está definida le asinga un valor nulo */
function valor_variable(variable){
  return typeof variable === 'undefined' ? null : variable;
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
			/*
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
            },*/
           highlight: function(input) {
             // $(input).addClass('error_input');
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
		for(key in data){ $('#'+ key).html(data[key]); } 
		
        if(! data.errors)
        {  
           $('#' + formulario + ' .error').html('');
           if(valor_variable(data.redirect)) { ruta(data.redirect); } 
		   else
		   {
			    $('#contenido_modal').html('<div align="center" style="margin: 30px"><b>'+ data.info + '</b></div>'); 
				setTimeout(function() 
				{
				  $('#modal_form').modal('hide'); 				  			   
				  dtable_serverSide.ajax.reload(null, false);
				}, 1500);			   
		   }

           // dtable_serverSide.ajax.reload(null, false);                           
        }

        $('#' + formulario).find("*").removeAttr('disabled'); // habilita el formulario
        $('#' + formulario + ' .deshabilitado').attr('disabled', 'disabled').removeClass('deshabilitado');        
    } 
    
  });     	   
}

/* carga la ruta enviada; si "form" está definida envia mediante
 * Post el contenido del formulario: form */
function carga_modal(ruta)
{        
	$.ajax({
      cache: false,
      url: php_baseurl + ruta, 
      type: 'POST', 
	  dataType: 'JSON',
      success: function (response) {             
          // $('#modal_form').modal('hide'); 
		  $('#contenido_modal').html(response.view); 
		  $('#modal_form .modal-title').html(response.title);
		  $('#modal_form').modal('show'); 
      }        
   });    
}
	  