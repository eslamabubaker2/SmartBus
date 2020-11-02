<?php

namespace App\Http\Controllers\Api\v1\parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Son;
use App\Models\Arrival;
use Notification;
use App\Models\transportor;
use App\Models\Rating;
use App\User;
use App\Models\School;

use Carbon\Carbon;



class ArrivalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allarrival()
    {

        $locale = \App::getLocale();
        $son= Son::where('parent_id',\Auth::user()->id)->get();


        foreach ($son as $item) {

            $Arrival=Arrival::select('id','son_id','name_day_'.$locale.' as name','going','timereturn','date','school_id','cancel_arrive')
            ->with(['Students'=>function($query) {
                $query->select('id','name');},
                'school'=> function($query) {
                $query->select('id','beginning_of_time');},
                'rating'=> function($query) {
                    $query->select('id','arrival_id','content_rating','driver_id');},

                ])->where('son_id', $item->id)->orderBy('id','desc')->paginate(1);
            $success['items'] = $Arrival;
            return apiSuccess($success);



        }



    }


    public function cancelarrival(Request $request,$id)
    {


        $Arrival=Arrival::where('id', $id)->first();
        if($Arrival->Sure_go==0)
        $Arrival->cancel_arrive=1;
        $Arrival->update();
        $son=Son::find($Arrival->son_id);

        $user=User::where('no_bus',$Arrival->transport_id)->first();
        $user->notify(new \App\Notifications\CancelArrivalNotifiction($son));
        return apiSuccess(null,200,'smartbus.notified_cancellation');


    }

    public function AddRating(Request $request,$id)
    {
       $Arrival=Arrival::where('id', $id)->first();
       $transpot=transportor::where('id',$Arrival->transport_id)->first();
        $respones = getFirstError($request, [
            'rating' => 'required|integer',
            'content_rating' => 'required',

        ],[

            'rating.required' => ' عليك ادخال التقييم',
            'content_rating.required' => 'عليك ادخال محتوى التقييم ',
        ]);

        if ($respones[IS_ERROR] == true) {
            return apiError($respones[ERROR]);
        }
      $ratin= Rating::create([
            'arrival_id' =>$id,
            'parent_id'=>\Auth::user()->id,
            'son_id' => $Arrival->son_id,
            'driver_id' =>  $transpot->driver_id,
            'rating' => $request->rating,
            'content_rating' => $request->content_rating,

        ]);

        return apiSuccess(null,200,'smartbus.TheRating_was_successful');

    }
   


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllRating($driver_id)
    {

        $Rating = Rating::where('driver_id',$driver_id)->where('parent_id', \Auth::user()->id)
        ->select('id','created_at','content_rating','rating')->get();
        $success['items'] = $Rating;
        return apiSuccess($success);
    }

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
