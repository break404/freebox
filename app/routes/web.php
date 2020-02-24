<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () {
    return redirect('/login');
});


Route::group(['prefix' => 'proxy', 'middleware' => ['user']], function () {
    Route::get('/v2ray', 'V2rayController@index');
    Route::get('/v2ray/data', 'V2rayController@data');
    Route::get('/v2ray/create', 'V2rayController@create');
    Route::get('/v2ray/edit', 'V2rayController@edit');
    Route::post('/v2ray/store', 'V2rayController@store');
    Route::post('/v2ray/update', 'V2rayController@update');
    Route::post('/v2ray/delete', 'V2rayController@delete');
    Route::post('/v2ray/app', 'V2rayController@app');

});


Route::group(['prefix' => 'network', 'middleware' => ['user']], function () {
    Route::get('/init', 'InitController@init');
    Route::get('/index', 'NetworkController@index');
    Route::get('/wanset', 'NetworkController@wanset');


    Route::post('/set_dhcp_reconnect', 'NetworkController@set_dhcp_reconnect');
    Route::post('/set_dhcp', 'NetworkController@set_dhcp');
    Route::post('/set_static', 'NetworkController@set_static');
    Route::post('/set_pppoe', 'NetworkController@set_pppoe');

    Route::get('/lanset', 'NetworkController@lanset');
    Route::get('/wifiset', 'NetworkController@wifiset');
    Route::get('/dhcpset', 'NetworkController@dhcpset');

});

Route::group(['prefix' => 'user', 'middleware' => ['user']], function () {
    Route::get('/changepwd', 'UserController@changepwd');

});

Route::group(['prefix' => 'system', 'middleware' => ['user']], function () {
    Route::get('/reboot', 'SystemController@reboot');
    Route::get('/upgrade', 'SystemController@upgrade');

});




$router->get('/login', 'UserController@login');
$router->get('/logout', 'UserController@logout');

