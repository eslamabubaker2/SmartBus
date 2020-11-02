<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::group(['prefix' => 'v1' , 'namespace' => 'Api\v1',"middleware" => ["lcalization"]] , function () {
    Route::POST('Register', 'AuthController@Register');
    Route::post('ForgetPassword', 'AuthController@ForgetPassword');
    Route::get('getAllschool', 'SchooApiController@index');
    Route::get('getAllcities', 'CityController@index');
    Route::post('resetpassword', 'AuthController@changepassword');
    Route::post('activateuser', 'AuthController@Activateuser');
    Route::post('AnotherSendCode', 'AuthController@AnotherSendCode');
    Route::get('Change/removecoordinate/bus/{idbus}', 'AuthController@removecoordinate');




    Route::post('login', 'AuthController@login');
    Route::group(["middleware" => ["auth:api"]], function () {
        Route::post('adressuser', 'AuthController@adressuser')->middleware('IsMobileActivate');
        Route::put('EditPassword/{id?}', 'AuthController@changePasswordd');
        Route::post('Editinfo/{idschooleditdriver?}', 'AuthController@Editinfo');
        Route::put('EditMobileno', 'AuthController@changemobileno');
        Route::get('allNotification', 'AuthController@allNotification');


    });

    Route::group(['prefix' => 'Parent' ,'namespace' => 'Parent',"middleware" => ["auth:api","IsParent","IsMobileActivate"]], function () {
        Route::get('getAllschool', 'SchooApiController@index');
        Route::post('AddNewSon', 'SonApiController@store');
        Route::get('getAllSon', 'SonApiController@index');
        Route::get('profileson/{id}', 'SonApiController@profileson');
        Route::delete('RemoveSon/{id}', 'SonApiController@destroy');
        Route::post('Editeprofileson/{id}', 'SonApiController@update');
        Route::post('CancellArrival/{id}', 'ArrivalController@cancelarrival');
        Route::post('AddRating/{id}', 'ArrivalController@AddRating');
        Route::get('allarrival', 'ArrivalController@allarrival');
        Route::get('getAllRating/{driver_id}', 'ArrivalController@getAllRating');

        Route::get('FirstlayoutParent', 'SonApiController@FirstlayoutParent');
        Route::get('FirstlayoutPar/{idstudent}', 'SonApiController@FirstlayoutPar');








    });

    Route::group(['prefix' => 'School' ,'namespace' => 'School',"middleware" => ["auth:api","IsSchool","IsMobileActivate"]], function () {
        Route::get('Students/{state}/{name?}', 'studentApiController@index');
        Route::put('Agreenewstudents/{id}', 'studentApiController@editaAgreenewstudents');
        Route::put('DisAgree/{id}', 'studentApiController@editDisAgreenewstudent');
        Route::put('DisAgree/{id}', 'studentApiController@editDisAgreenewstudent');
        Route::post('AddNewdriver', 'BusController@store');
        Route::put('changebus/{id}', 'BusController@update');
        Route::get('getAllDriver/{name?}', 'BusController@index');
        Route::get('getDriver/{id}', 'BusController@getDriver');

        Route::post('AddBus', 'transportorController@store');//الباص
        Route::put('AddNewCoordinateBus/{id}', 'transportorController@AddNewCoordinateBus');//الباص
        Route::put('EditBus/{id}', 'transportorController@update');//الباص

        Route::get('getAllBus/{nobus?}', 'transportorController@index');//alltransportor
        Route::post('SendNotificationToDriver/{id}', 'BusController@SendNotificationToDriver');
        Route::get('getAllStudentBus/{id}', 'studentApiController@getAllStudentBus');
        Route::put('EditStudentBus/{id}', 'studentApiController@EditStudentBus');
        Route::post('Editinfodriver/{id}', 'DirectoreController@Editinfodriver');
        Route::post('EditAttachment/{id}', 'DirectoreController@EditAttachment');
        Route::put('EditMobileno/{iddriver}', 'DirectoreController@changemobileno');
        Route::get('profilestudent/{id}', 'ArrivalController@profilestudent');
        Route::delete('Removestudent/{id}', 'DirectoreController@destroy');
        Route::get('allarrival/{idstudent}', 'ArrivalController@allarrival');
        Route::get('AllarrivalMystudent/{state}', 'ArrivalController@AllarrivalMystudent');
        Route::get('getAllRating/{iddriver}', 'ArrivalController@getAllRating');

        Route::get('FirstlayoutSchool', 'studentApiController@FirstlayoutSchool');
        Route::get('FirstlayoutSchoo/{idstudent}', 'studentApiController@FirstlayoutSchoo');































    });

    Route::group(['prefix' => 'driver' ,'namespace' => 'driver',"middleware" => ["auth:api","IsBus","IsMobileActivate"]], function () {

        Route::get('getAllStudentBus/{name?}', 'studentApiController@getAllStudentBus');
        Route::get('profilestudent/{id}', 'ArrivalController@profilestudent');
        Route::post('ConfirmGoing/{id}', 'ArrivalController@ConfirmGoing');
        Route::post('ConfirmReturn/{id}', 'ArrivalController@ConfirmReturn');
        Route::get('profilestudent/{id}', 'ArrivalController@profilestudent');
        Route::get('allrating/{idstudent}', 'ArrivalController@allrating');
        Route::get('AllarrivalMyStudent', 'ArrivalController@AllarrivalMyStudent');
        Route::get('adress/school/parent/bus/{idstudent}', 'ArrivalController@adress_school_parent_bus');
        Route::get('getAllRating', 'ArrivalController@getAllRating');
        Route::get('FirstlayoutDriver', 'ArrivalController@FirstlayoutDriver');
        Route::get('FirstlayoutDrive/{idstudent}', 'ArrivalController@FirstlayoutDrive');





    });
});
