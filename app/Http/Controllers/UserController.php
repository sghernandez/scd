<?php
    
namespace App\Http\Controllers;
    
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use App\Models\User;
use App\Util\Util;
use DataTables;
use Hash;
use DB;

    
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $columns_datatables = [0 => 'id', 'name', 'lastname', 'document', 'email', 'roles', 'action'];
        $order = '5,6';  // columnas sin ordenación
        $ruta = 'users.index'; 

        if ($request->ajax()) 
        {          
            $j = 0;
            $search = trim($request->all()['columns'][1]['search']['value']);     
            $o_order = $request->order;
         
            $orderCol = isset($o_order[0]['column']) ? $o_order[0]['column'] : '';
            $col = isset($columns_datatables[$orderCol]) ? $columns_datatables[$orderCol] : 'name';
            $dir = isset($o_order[0]['dir']) ? strtoupper($o_order[0]['dir']) : 'ASC';              

            $query = User::query();
            $query->orderBy($col, $dir);

            if($search)
            {
                foreach (['id', 'name', 'lastname', 'document', 'email'] as $field) 
                {
                    $where = $j == 0 ? 'where' : 'orWhere';            
                    $query-> { $where } ($field, 'LIKE', $query->raw("'%$search%'"));
                    $j++;
                }  
            }              

            return DataTables::of($query->get())   
                    ->addColumn('roles', function($row)
                    {   
                        $roles = '';
                        if(! empty( $row->getRoleNames() ) )
                        {
                            foreach($row->getRoleNames() as $v){
                                $roles .= '<label class="badge badge-success">'. $v. '</label>';
                            }
                        }               

                        return $roles;
                    })                                                                                             
                    ->addColumn('action', function($row){                        
                        $fn_edit = "carga_modal('" . route('users.edit', $row->id) . "')";
                        return '<div align="center"><span onclick="' . $fn_edit . '" class="btn btn-info btn-xs"><i class="fa fa-edit"></i></span></div>';        
                    })
                    ->rawColumns(['roles', 'action']) // dibuja el html
                    ->make(true);            
        }        

        return view('users.index', compact('columns_datatables', 'order', 'ruta')); 
    }

    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {    
        $title = 'Nuevo Usuario';
        $view = view('users.form_user', ['roles' => Role::pluck('name', 'name')->all()])->render();
        
        return json_encode(compact('view', 'title'));             
    }

    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try { 
            $this->validate($request, [
                'lastname' => 'required',
                'name' => 'required',
                'document' => 'required|unique:users,document',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|same:confirm-password',
                'roles' => 'required'
            ]);
        } 
        catch (ValidationException $e) { return Util::form_errors($e); }            
    
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
    
        $user = User::create($input);
        $user->assignRole($request->input('roles'));
    
        return json_encode (['success' => 'Usuario guardado correctamente.']); 
    }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {    
        return view('users.show', ['user' => User::find($id)]);
    }
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        
        $title = 'Editar Usuario';
        $view = view('users.form_user', compact('user', 'roles', 'userRole'))->render();
        
        return json_encode(compact('view', 'title'));             
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request->input('id');
        if($id == 1) { return redirect('/'); }

        try { 
            $this->validate($request, [
                'lastname' => 'required',
                'name' => 'required',
                'email' => 'required|email|unique:users,email,'. $id,            
                'document' => 'required|unique:users,document,'. $id,            
                'password' => 'same:confirm-password',
                'roles' => 'required'
            ]);
        } 
        catch (ValidationException $e) { return Util::form_errors($e); }              
    
        $input = $request->all();
        if(! empty($input['password'])){  $input['password'] = Hash::make($input['password']); }
        else{ $input = Arr::except($input ,array('password')); }
    
        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id', $id)->delete();
        $user->assignRole($request->input('roles'));
            
        return json_encode (['success' => 'Usuario actualizado correctamente.']);    
    }
    

}