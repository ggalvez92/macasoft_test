<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class LoginController extends Controller
{
    public function index()
    {
        if(Auth::check())
            return redirect()->route('dashboard.index');

        return view('login.index');
    }

    public function login()
    {
    	$messages = [
            'email.required' => 'El correo es obligatorio',
            'email.email' => 'Escriba un correo válido',
            'password.required' => 'La clave es obligatoria',
        ];

        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        request()->validate($rules, $messages);

        $url = "";

        if( Auth::attempt(
                        [
        				 'email' => request('email'),
        				 'password' => request('password')
        				],true) )
        {
        	$error = FALSE;
        	$type = 3;
        	$title = "OK!";
            $msg = "Bienvenido, " . Auth::user()->name;
            $url = route('dashboard.index');
        } else {
           	$error = TRUE;
	    	$type = 2;
	    	$title = "Error!";
	    	$msg = "Verifique los datos ingresados";
        }

        return response()->json([
        	'error' => $error,
        	'type' => $type,
        	'title' => $title,
        	'msg' => $msg,
        	'url' => $url
        ],200);
    }

    public function logout()
    {
        Auth::logout();
        
        return redirect()->route('home');
    }
}
