<?php

Route::get('/',[
	'uses' => 'LoginController@index',
	'as' => 'home'
]);

Route::post('login/ct',[
	'uses' => 'LoginController@login',
	'as' => 'login'
]);

Route::group(['middleware' => 'auth'], function(){

	/******************INICIO**********************/
	Route::get('dashboard',[
		'uses' => 'BackendController@index',
		'as' => 'dashboard.index'
    ]);
    
    Route::get('logout',[
		'uses' => 'LoginController@logout',
		'as' => 'logout'
	]);
	/************************************************/

    // Para evitar que un usuario que no sea administrador pueda 
    // acceder a los metodos de crear,editar,listar y eliminar
    Route::group(['middleware' => 'admin'], function(){
    /******************USUARIOS**********************/
        Route::post('user/roles/list',[
            'uses' => 'UserController@rolesList',
            'as' => 'user.roles.list'
        ]);

        Route::post('user/ct',[
            'uses' => 'UserController@ct',
            'as' => 'user.ct'
        ]);

        Route::post('user/list',[
            'uses' => 'UserController@list',
            'as' => 'user.list'
        ]);

        Route::post('user/detail',[
            'uses' => 'UserController@detail',
            'as' => 'user.detail'
        ]);

        Route::post('user/delete',[
            'uses' => 'UserController@delete',
            'as' => 'user.delete'
        ]);
        /************************************************/
    });
});