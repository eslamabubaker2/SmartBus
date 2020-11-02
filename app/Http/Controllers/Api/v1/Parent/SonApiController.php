<?php

namespace App\Http\Controllers\Api\v1\Parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Eloquent\SonRepository;
use App\Http\Requests\SoneRequest;
use App\Models\Son;
use App\Models\Notification;
use App\Models\Arrival;

use DB;
use App;
use Carbon\Carbon;

use App\Models\School;

class SonApiController extends Controller
{
    protected $Son;
    public function __construct(SonRepository $Son)
    {
        $this->Son = $Son;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $Son=Son::with(['school' => function($query) {
        $query->select('id', 'name');}])->where('Is_agree',1)->orwhere('Is_agree',0)->where('parent_id',auth('api')->user()->id)->get();
        $success['items']=$Son;
        return apiSuccess($success);
    }



    public function profileson($id)
    {
        $locale=App::getLocale();


        // ->select('name_day_'.$locale . ' as name')
        $Son=Son::with([ 'parents' => function($query) {
            $query->select('id', 'phone_no','text_adress');},
            'school' => function($query) {
            $query->select('id', 'name','beginning_of_time');},

               'transportor.driver' => function($query) {
            $query->select('id','firstname','secondname');},
                 'arrival' => function($query) use ($locale){
            $query->select('id','son_id','name_day_'.$locale.' as name','going','timereturn','date','cancel_arrive','Sure_go','Sure_return');},
            'arrival.rating' => function($query) use ($locale){
            $query->select('id','arrival_id','rating');}
        ])->where('id',$id)->get();
        $success['items'] = $Son;
        return apiSuccess($success);
    }


    public function FirstlayoutParent()
    {


      $Sons=Son::where('parent_id',auth('api')->user()->id)->where('Is_agree', 1)->get();

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

    
    public function FirstlayoutPar($idstudent)
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SoneRequest $request)
    {
        $Son= $this->Son->store($request->all());
        $success['items']=$Son;
        return apiSuccess($success,200,'smartbus.Added_succesfully');

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
        $Son=Son::where('id',$id)->first();
        if (isset($request->name)) {
            $Son->name = $request->name;
        }
        if (isset($request->gender)) {
            $Son->gender = $request->gender;
        }
        $Son->update();
        return apiSuccess($Son, 200, 'smartbus.Modified_successfully');


        }




    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Son=Son::where('id',$id)->where('Is_agree',[0,2])->delete();
        return apiSuccess(null, 200,'smartbus.cancelled_successfully');

    }
}
