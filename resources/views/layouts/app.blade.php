<!DOCTYPE html>
<html>
<head>
    <title>{{ Config::get('app.app_name') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
	
    <link href="{{ asset('css/app.css') }}" rel="stylesheet" type="text/css" />                
    <link href="{{ asset('css/fontawesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css" />        
    <link href="{{ asset('css/jquery-ui.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/jquery-ui-1.10.0.custom.css') }}" rel="stylesheet" type="text/css" />		    
    <link href="{{ asset('css/dataTables.bootstrap.min.4.1.10.19.css') }}" rel="stylesheet" type="text/css" />
    
    <style>
        #dataTable_filter{display: none !important}
        .hide_filter .dataTables_filter { display: none; }
        .error{color: #843534; font-weight: bold; }
        .error_input { 
            border-color: #FCD4D9 !important; 
            border-width: thick ;
            border-style: solid;
        }
        .deshabilitado{ background-color: #F9F9F9; }

        .uper {
            margin-top: 40px;
        }
    </style> 	
</head>
<body>
    
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}" style="font-size: 22px !important">
                    {{ Config::get('app.app_name') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
    
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto"></ul>


                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
                            <li><a class="nav-link" href="{{ route('register-user') }}">{{ __('Register') }}</a></li>
                        @else

                        <li><a class="nav-link" href="{{ route('diezmos') }}">Diezmos</a></li>
                        @can('super-admin')                            
                            <li><a class="nav-link" href="{{ route('users.index') }}">Usuarios</a></li>
                            <li><a class="nav-link" href="{{ route('roles.index') }}">Roles</a></li>            
                        @endcan
                             
                            
                            @canany(['manager', 'another-perm'])
                            <!-- <li><a class="nav-link" href="">Ruta</a></li>

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    Opciones<span class="caret"></span>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">                                        
                                    <a class="dropdown-item" href="">Opción A</a>
                                </div>                                
                            </li> -->
                            @endcanany                         

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">                                        
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">                
               @yield('content')
            </div>
        </main>
    </div>

    <!-- Modal -->
    <div id="modal_form" class="modal fade" role="dialog">
        <div class="modal-dialog">
  
          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">              
              <h4 class="modal-title text-center"></h4>
            </div>
              <div class="modal-body" id="contenido_modal">                
            </div>
  
          </div>
  
        </div>
      </div>      

</body>
   
<script type="text/javascript"> var base_url = '{{ asset('/') }}'; </script>
<script src="{{ asset('js/jquery.1.9.1.js') }}"></script>
<script src="{{ asset('js/jquery-ui-1.10.4.min.js') }}"></script>
<script src="{{ asset('js/jqueryValidation-1.17.0/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.4.1.3.js') }}"></script>
<script src="{{ asset('js/functions.js') }}"></script>

@if(isset($columns_datatables))
<script src="{{ asset('js/jquery.dataTables.min.1.10.16.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap.min.4.1.10.19.js') }}"></script>

<script type="text/javascript">
  $(function () {
    
     dtable_serverSide = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route($ruta) }}",
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
		    },        
        columns: [
        <?php
            $order = array_flip( explode(',', ( isset($order) ? $order : '' ) ) );     

            foreach($columns_datatables as $key => $col)
            {
                $obj = new \stdClass();
                $obj->data = $obj->name = $col;

                if(isset($order[$key])) {
                    $obj->{ 'orderable' } = false; 
                }

                $obj->sClass = 'text-center';
                echo json_encode($obj). ', ';
           }  ?>          
        ]
    });
    
  });
</script>
@endif
</html>
