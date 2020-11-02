<?php

namespace App\Http\Controllers\Api\v1\driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Son;
use App\Models\Arrival;
use App\User;
use App\Models\Rating;

use Carbon\Carbon;


class ArrivalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function profilestudent($id)
    {
        $locale=\App::getLocale();
        $Son=Son::with([ 'parents' => function($query) {
            $query->select('id', 'phone_no','text_adress');},
            'school' => function($query) {
            $query->select('id', 'name','beginning_of_time');},

               'transportor.driver' => function($query) {
            $query->select('id','firstname','secondname');},
                 'arrival' => function($query) use ($locale){
            $query->select('id','son_id','name_day_'.$locale.' as name','going','timereturn','date','cancel_arrive');},
            'arrival.rating' => function($query) use ($locale){
            $query->select('id','arrival_id','rating');}
        ])->where('id',$id)->get();
        $success['items'] = $Son;
        return apiSuccess($success);
    }
    public function AllarrivalMyStudent()
    {
        $locale = \App::getLocale();
        $bus=User::find(\Auth::user()->id);
        $son=Son::where('transport_id',$bus->no_bus)->get();

        foreach ($son as $item) {

            $Arrival=Arrival::select('id','son_id','name_day_'.$locale.' as name','going','timereturn','date','school_id')
            ->with(['Students.parents'=>function($query) {
                $query->select('id','phone_no','text_adress');},

                'school'=> function($query) {
                $query->select('id','beginning_of_time','name');},
                'rating'=> function($query) {
                    $query->select('id','arrival_id','content_rating','driver_id');},

                ])->where('son_id', $item->id)->orderBy('id','desc')->paginate(1);
            $success['items'] = $Arrival;
            return apiSuccess($success);
        }

}


public function adress_school_parent_bus($id)
{
    $Son=Son::with(['school' => function($query) {
        $query->select('id', 'latitude','longitude');},

           'parents' => function($query) {
        $query->select('id', 'latitude','longitude');},

             'transportor' => function($query) {
        $query->select('id', 'start_latitude','start_longitude');}

    ])->where('id',$id)->get();

    $success['items'] = $Son;
    return apiSuccess($success);



}


public function FirstlayoutDriver()
    {

        $user=User::where('id',auth('api')->user()->id)->first();
       
    $Sons=Son::where('transport_id',$user->no_bus)->where('Is_agree', 1)->get();
 

      if(isset( $Sons)){
      foreach($Sons as $item){
        $locale=\App::getLocale();
        $date = Carbon::now();
        $arr= Arrival::with(['student'=> function($query) {
            $query->select('id', 'name');}])->where('son_id',$item->id)->where('date',$date->toFormattedDateString())->select('id','son_id','name_day_'.$locale.' as name','going','timereturn','date','Sure_go','Sure_return','cancel_arrive')->get();
        
         $success['items'] = $arr;
         return apiSuccess($success);
      }
    }
      else{
        return apiSuccess(null); 
      }

    }

    
    public function FirstlayoutDrive($idstudent)
    {

        $Sons=Son::with(['parents' => function($query) {
            $query->select('id', 'longitude','latitude');}, 'school' => function($query) {
            $query->select('id', 'longitude','latitude');},'transportor'=> function($query) {
            $query->select('id', 'start_latitude','start_longitude');}])->where('id', $idstudent)->select('id','name','school_id','parent_id','transport_id')->where('Is_agree', 1)->get();

        $success['items'] = $Sons;
        return apiSuccess($success);
        }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function ConfirmGoing(Request $request, $id)
    {
        $ConfirmGoing=Arrival::where('id', $id)->first();
        $ConfirmGoing->Sure_go=1;
        $ConfirmGoing->update();
        $success['items'] = $ConfirmGoing;
        return apiSuccess($success,200,'smartbus.Go_has_been_confirmed');
    }
    public function ConfirmReturn(Request $request, $id)
    {
        $ConfirmGoing=Arrival::where('id', $id)->first();
        $ConfirmGoing->Sure_return=1;
        $ConfirmGoing->update();
        $success['items'] = $ConfirmGoing;
        return apiSuccess($success,200,'smartbus.Return_has_been_confirmed');

    }
    public function getAllRating()
    {


        $Rating = Rating::where('driver_id',\Auth::user()->id)
        ->select('id','created_at','content_rating','rating')->orderBy('id','desc')->paginate(1);
        $success['items'] = $Rating;
        return apiSuccess($success);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
