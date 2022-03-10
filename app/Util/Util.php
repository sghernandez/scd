<?php

namespace App\Util;

use App\Models\User;
use Illuminate\Support\Facades\DB;
# use Illuminate\Http\Request;

class Util
{

    /* userHasRole: verifica si el usurio tiene el rol solicitado */
    public static function userHasRole($role,  $idUser = null){     
        return in_array($role, Util::userRoles($idUser));
    }


    /* userHasRole: verifica si el usurio tiene un rol de los solicitados */
    public static function atLeastOneRole($roles = [],  $idUser = null)
    {     
        $hasRoles = Util::userRoles($idUser);

        foreach($roles as $rol){
             return in_array($rol, $hasRoles);
        }
       
        return FALSE;
    }


    /* userRoles: retorna un array con los roles del usuario */ 
    public static function userRoles($idUser = null)
    {
        $user = User::find($idUser ? : auth()->user()->id);
        return $user->roles->pluck('name','name')->all();   
    }


    /* getUsers: retorna un arreglo clave => valor | idusuario => nombre apellidos / documento  */
    public static function getUsers($id=0)
    {
        $USER = new \App\Models\User;
        $users[null] = 'Seleccione Usuario'; 
        $us = $id ? $USER::find($id) : $USER::all();

        foreach($us as $u){
            $users[$u->id] = "$u->name $u->lastname / $u->document";
        }

        return $users;
    } 


    /* timepstamps: para completar los timestamps cuando no se usa un modelo */
    public static function timepstamps($array, $update=FALSE)
    {
       $time = date('Y-m-d H:i:s');
       ! $update ? $array['created_at'] = $time : '';
       $array['updated_at'] = $time;

       return $array;

    }
    

    /* ---------------------------------------------  */
    public static function registros($collection, $colspan)
    {
        $td = "<tr><td colspan='$colspan'>No se hallaron resultados.</td></tr>";

        foreach($collection as $r)
        {
            $td = '';
            break;
        }

        return $td;
    }

    public static function form_errors($e) {
       return json_encode (array_merge($e->errors(), ['errors' => true]));
    }    

    
} // finaliz la clase