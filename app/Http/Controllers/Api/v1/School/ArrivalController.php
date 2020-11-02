<?php

namespace App\Http\Controllers\Api\v1\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Son;
use App\Models\Arrival;
use App\Models\Rating;
use App\User;

class ArrivalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {

    }

    public function AllarrivalMystudent($state)
    {
 $user=User::find(\Auth::user()->id);
if($state==1){
       

        $Arrival = Arrival::with(['Students.parents'=>function($query) {
            $query->select('id','phone_no','text_adress');},

            'school'=> function($query) {
            $query->select('id','beginning_of_time','name');},
            'rating'=> function($query) {
                $query->select('id','arrival_id','content_rating','driver_id');},])->where([['school_id',$user->school_id],['Sure_go',0]])->orderBy('id','desc')->paginate(5);
             
            }
            elseif($state==0){
                $Arrival = Arrival::with(['Students.parents'=>function($query) {
                    $query->select('id','phone_no','text_adress');},
        
                    'school'=> function($query) {
                    $query->select('id','beginning_of_time','name');},
                    'rating'=> function($query) {
                        $query->select('id','arrival_id','content_rating','driver_id');},])->where([['school_id',$user->school_id],['Sure_go',1],['Sure_return',1]])->orderBy('id','desc')->paginate(5);
            }



        $success['items'] = $Arrival;
        return apiSuccess($success);
    }

    public function profilestudent($id)
    {
        $locale = \App::getLocale();
        $Son = Son::with(['school' => function ($query) {
            $query->select('id', 'beginning_of_time');
        },

            'parents' => function ($query) {
                $query->select('id', 'phone_no', 'text_adress');
            },
            'transportor' => function ($query) {
                $query->select('id', 'no_bus', 'driver_id');
            },

            'transportor.driver' => function ($query) {
                $query->select('id', 'firstname', 'secondname');
            },

            'arrival' => function ($query) use ($locale) {
                $query->select('id', 'son_id', 'name_day_' . $locale . ' as name', 'going', 'timereturn', 'date');
            },
            'arrival.rating' => function ($query) {
                $query->select('id', 'rating', 'arrival_id');
            },

        ])->where('id', $id)->get();

        $success['items'] = $Son;
        return apiSuccess($success);
    }

    public function getAllRating($iddriver)
    {
        $Rating = Rating::where('driver_id', $iddriver)->get();
        $success['items'] = $Rating;
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
