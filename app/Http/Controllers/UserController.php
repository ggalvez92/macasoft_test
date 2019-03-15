<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\User;
use App\Role;

class UserController extends Controller
{
    public function rolesList()
    {
        $roles = Role::select('id','name')->get();

    	return response()->json(['roles' => $roles],200);
    }
    
    public function ct()
    {
        $auth = Auth::user();
        $id = request('id');

        $messages = [
            'name.required' => 'El nombre  es obligatorio.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'Escriba un correo válido.',
            'email.unique' => 'Este correo está siendo usado.',
            'password.required' => 'La clave es obligatoria.',
            'password.min' => 'La clave debe tener mínimo :min caractéres.',
            'password.required_with' => 'Las claves no coinciden.',
            'password.same' => 'Las claves no coinciden.',
            'password_confirmation.required' => 'La clave es obligatoria.',
            'password_confirmation.min' => 'La clave debe tener mínimo :min caractéres.',
            'roles.required' => 'Seleccione al menos un rol.',
            'image.required' => 'El logo es obligatoria.',
            'image.image' => 'Tiene que ser una imagen válida.',
            'image.mimes' => 'Use un formato válido :values.',
            'image.max' => 'La imagen máximo puede pesar :max kb.',
        ];

        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'. $id .',id,deleted_at,NULL',
            'password' => 'required|min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'required|min:6',
            'roles' => 'required',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:128',
        ];

        if(!is_null($id))
        {
            $rules['image'] = 'image|mimes:jpg,jpeg,png|max:128';
            $rules['email'] = 'email|unique:users,email,'. $id .',id,deleted_at,NULL';
            if(!request('password'))
            {
                unset($rules['password']);
                unset($rules['password_confirmation']);
            }
        }

        request()->validate($rules, $messages);

        $flag = false;

        if(is_null($id))
        {
            $o = new User();
        } else {
            $o = User::where('id',$id)->first();
            $last_name = $o->name;
            $flag = true;
        }

        $o->name = request('name');
        $o->email = request('email');
        $o->password = request('password');

        if(is_null($id))
        {
            $o->save();
            $msg = "Usuario creado correctamente";
        } else {
            $o->update();
            $msg = "Usuario actualizado correctamente";
        }

        $o->roles()->sync(request('roles'));

        $file = request()->file('image');
        if($file)
        {
        	$c_name = null;
        	if($flag && $last_name != $o->name){
        		$c_name = $o->url_image;
        	}
            $filename = $o->id . "-" . $o->name;
            $url_image = app('App\Http\Controllers\UtilitiesController')->addFile('public','users',$file,$filename,$c_name);
            $o->url_image = $url_image;
            $o->update();
        } else if($flag){
            if($last_name != $o->name && !is_null($o->url_logo))
            {
            	$filename = $o->id . "-" . $o->name;
            	$last_name_url = $o->url_image;
            	$url_image = app('App\Http\Controllers\UtilitiesController')->moveFile($last_name_url,$filename,'public','users');
	            $o->url_image = $url_image;
	            $o->update();
            }
        }

        $error = FALSE;
    	$type = 1;
    	$title = "OK!";
    	$url = "";


    	return response()->json([
        	'error' => $error,
        	'type' => $type,
        	'title' => $title,
        	'msg' => $msg,
        	'url' => $url
        ],200);
    }

    public function list(Request $request)
    {
        $auth = Auth::user();

        DB::statement(DB::raw('SET @row_number = 0'));

        $objs = User::query();
                    
        //Filtro por nombre
        $objs->where('name','like','%'.request('name').'%');

        //Filtro por rol
        if( request('role_id') ) {
            $objs->whereHas('roles', function ($query) {
                $query->where('roles.id',request('role_id'));
            });
        }

        //Filtro para no mostrar el mismo usuario que está logeado
        $objs = $objs->where('id','!=',$auth->id)
            ->select(
                DB::raw('(@row_number:=@row_number + 1) AS n'),
                'id',
                'name',
                'email',
                'url_image'
            )
            ->with(array('roles' => function($query){
                $query->select('roles.id','roles.name');
            }))
            ->orderBy('id','asc')
            ->paginate(20);

        //Recorro  la colección para poner la ruta exacta de la imagen y generar arreglo de los roles
        $objs->map(
            function($item,$key){
                //para que funcione esta ruta se tiene que correr el siguiente comando
                // php artisan storage:link
                $item->url_image = $item->url_image ? asset('storage/'.$item->url_image) : "";
                $item->roles_list = implode(", ",$item->roles->pluck('name')->toArray());
                return $item;
            });
        
        return response()->json([
            'pagination' => [
                'total' =>          $objs->total(),
                'current_page' =>   $objs->currentPage(),
                'per_page' =>       $objs->perPage(),
                'last_page' =>      $objs->lastPage(),
                'from' =>           $objs->firstItem(),
                'to' =>             $objs->lastPage(),
            ],
            'users' => $objs->items(),
        ],200);
    }

    public function detail()
    {
        $roles = Role::select('id','name')->get();

        $id = request('id');
        $o = null;
        if(!is_null($id))
        {
            $o = User::where('id',$id)
                    ->select('id','name','email')
                    ->with(array('roles' => function($query){
                        $query->select('roles.id');
                    }))
                    ->first();

            // Se creará un collection para almacenar que roles posee el usuario
            // y con este array se podrá hacer un v-model en el frontend(vue.js)
            $o->roles_list = collect();
            foreach ($roles as $key => $r) {
                $aux = new \stdClass();

                $aux->id = $r->id;
                $flag = false;
                foreach ($o->roles as $rol_key => $rv) {
                    if($rv->id == $r->id){
                        $flag = true;
                        break;
                    }
                }

                $aux->value = $flag;
                $o->roles_list[] = $aux;
            }
        }

    	return response()->json(['user' => $o, 'roles' => $roles],200);
    }

    public function delete(Request $request)
    {
        $id = request('id');
        $e = User::where('id',$id)->first();
        
        $e->delete();

        $error = FALSE;
        $type = 1;
        $title = "OK!";
        $msg = "Usuario eliminado correctamente";
        $url = "";


        return response()->json([
            'error' => $error,
            'title' => $title,
            'msg' => $msg,
        ],200);
    }
}
