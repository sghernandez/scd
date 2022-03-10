<?php

namespace App\Http\Controllers;

use \Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Diezmo;
use App\Util\Util;
use DataTables;


class DiezmoController extends Controller
{

    /* carga el listado */
    public function index(Request $request)
    {        
        $columns_datatables = [0 => 'id', 'fecha', 'name', 'document', 'diezmo', 'ofrenda', 'entregado', 'action'];
        $order = '7';   // columnas sin ordenación ...separadas por "," sin espacios
        $ruta = 'diezmos';

        if ($request->ajax()) 
        {
            $rq = $request->all()['columns'];
                
            $inicio = $rq[1]['search']['value'];
            $limite = $rq[2]['search']['value'];
            $user_id = $rq[3]['search']['value'];

            if(! Util::userHasRole('super-admin')) { $user_id = auth()->user()->id; }

            $diezmos = DB::table('diezmos')
                ->selectRaw('diezmos.id, diezmos.observacion, diezmos.diezmo, diezmos.ofrenda, diezmos.entregado, diezmos.fecha, users.document, users.lastname, users.name') 
                ->when($inicio, function($query) use ($inicio) { $query->where('fecha', '>=', $inicio); })             
                ->when($limite, function($query) use ($limite) { $query->where('fecha', '<=', $limite); })              
                ->when($user_id, function($query) use ($user_id) { $query->where('users.id', $user_id); })                           
                ->join('users', 'diezmos.user_id', '=', 'users.id')       
                ->orderBy('fecha', 'DESC');                 

            return DataTables::of($diezmos) // ->addIndexColumn()
                    ->addColumn('name', function($row){
                        return $row->name. ' '. $row->lastname;
                    })  
                    ->addColumn('document', function($row){
                        return $row->document;
                    })                         
                    ->addColumn('entregado', function($row){ return $row->entregado ? 'Sí' : 'No'; })                        
                    ->addColumn('action', function($row){                        
                        $fn_edit = "carga_modal('" . route('diezmos.edit', $row->id) . "')";
                        return '<div align="center"><span onclick="' . $fn_edit . '" class="btn btn-info btn-xs"><i class="fa fa-edit"></i></span></div>';        
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }                       
        
        return view('diezmos.diezmos', compact('columns_datatables', 'order', 'ruta'));        
    }


    /* cargar el formulario para insertar un registro */
    public function create()
     {     
        $title = 'Nuevo Diezmo';
        $view = view('diezmos.form_diezmo', $this->form_data())->render();  	

        return json_encode(compact('view', 'title'));                
    }


    /* inserta un registro */
    public function store(Request $request)
    {
        extract($this->setRules($request));

        try { $request->validate($rules, $messages); } 
        catch (ValidationException $e) { return Util::form_errors($e); }      

        $id = DB::table('diezmos')->insertGetId(Util::timepstamps($data));   
        return json_encode (['success' => 'Diezmo con ID: '. $id. ' guardado correctamente.']);             
    }

   
    /* carga el formulario para editar */
    public function edit($id)
    {    
        $super_admin = Util::userHasRole('super-admin');
        
        $diezmo = DB::table('diezmos')            
            ->where('id', $id)     
            ->when(! $super_admin, function($query){ $query->where('user_id', auth()->user()->id); })
            ->get()
            ->first();   

        if(empty($diezmo->id) ) { 
            return redirect('diezmos')->with('success', "No es posible editar el diezmo con ID: $id.");
        }  

        $title = ($id ? 'Editar' : 'Nuevo'). ' Diezmo';
        $view = view('diezmos.form_diezmo', array_merge($this->form_data(), ['diezmo' => $diezmo]))->render();  		

        return json_encode(compact('view', 'title'));        
    }


    /*  método para actulizar */    
    public function update(Request $request)
    {  
        extract($this->setRules($request));
        $id = $request->input('id');

        try { $request->validate($rules, $messages); } 
        catch (ValidationException $e) { return Util::form_errors($e); }  
        
        $data = Util::timepstamps($data, TRUE);
        DB::table('diezmos')->where('id', $id)->update($data);  

        return json_encode (['success' => 'Diezmo con ID: '. $id. ' actualizado correctamente.']);        
    }


    /* retorna la información para el formulario */
    private function form_data()
    {
        $super_admin = Util::userHasRole('super-admin');
        
        return [
            'super_admin' => $super_admin,
            'users' => $super_admin ? Util::getUsers() : [],            
        ];

    }    


    /* reglas de validación del formulario */
    private function setRules(Request $request)
    {  
        $messages = [       
            'user_id.required' => 'Por favor seleccióne un usuario',     
            'diezmo.required' => 'Por favor ingrese un valor',
            'ofrenda.required' => 'Por favor ingrese un valor',
            'entregado.required' => 'Por favor elija una opción',
            'fecha.required' => 'Ingrese una fecha.',
        ];        

        $rules = [
            'diezmo' => 'required|numeric',  
            'ofrenda' => 'required|numeric|min:2', 
            'fecha' => 'required',    
            'entregado' => 'required|numeric',           
            'user_id' => 'required',  
        ];

        $data = $request->post();
        unset($data['_token'], $data['_method']);
        
        return compact('rules', 'messages', 'data');
        
    } 


   /* método para borrar */    
   public function destroy($id)
   {
       //
   }


}
