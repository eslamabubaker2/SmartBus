<?php

use Illuminate\Support\Facades\Route;
use App\Models\City;


// Route::get('/test','Api\v1\School\FirebaseController@index');

Route::get('/index', function () {
    return view('cpanel.layout.index');
});
Route::get('/addcity', function () {

});

Route::group(['prefix'=>'admin','namespace'=>'Admin'], function () {
    Route::get('/login', 'AdminController@ShowformLogin')->name('admin.login');
    Route::POST('/adminlogin', 'AdminController@adminLogin')->name('admin.login.lo');
    Route::post('/logout', 'AdminController@logout')->name('admin.logout');

Route::group(['middleware' => 'auth:admin'], function () {
    Route::get('/index', 'AdminController@index');


 });

});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
