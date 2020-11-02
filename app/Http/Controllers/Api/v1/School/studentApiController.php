<?php

namespace App\Http\Controllers\Api\v1\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Son;
use App\Models\Notification;
use App\User;
use App\Models\Arrival;
use Carbon\Carbon;

use App\Models\School;
class studentApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($state, $name = null)
    {
        $s = School::where('director_id', auth('api')->user()->id)->first();

        if ($name != null) {
            $user = Son::with(['parents' => function ($query) {
                $query->select('id', 'phone_no', 'text_adress');
            }])->where('name', 'LIKE', '%' . $name . '%')->where('Is_agree', $state)->where('school_id', $s->id)->get();
            if (count($user) > 0) {
                $success['items'] = $user;
                return apiSuccess($success);
            } else {
                return apiSuccess(null, 200, 'smartbus.student_not_found');
            }
        } else {

            $student = Son::with(['parents' => function ($query) {
                $query->select('id', 'phone_no', 'text_adress');
            }])->where('Is_agree', $state)->where('school_id', $s->id)->select('id', 'name', 'parent_id')->get();

            $success['items'] = $student;
            return apiSuccess($success);
        }


    }
    public function FirstlayoutSchool()
    {

      $s = School::where('director_id', auth('api')->user()->id)->first();

      $Sons=Son::where('school_id', $s->id)->where('Is_agree', 1)->get();

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



    public function FirstlayoutSchoo($idstudent)
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


    }

    public function editaAgreenewstudents(Request $request, $id)
    {

       
        $respones = getFirstError($request, [
            'no_bus' => 'required',
        ], [
                'no_bus.required' => ' تنبيه!عليك اختيار الباص ',

            ]
        );
        if ($respones[IS_ERROR] == true) {
            return apiError($respones[ERROR]);
        }


        // اعنوان الاب الطالب+عنوان المدرسة

        $Son = Son::with(['parents' => function ($query) {
            $query->select('id', 'latitude', 'longitude');
        }])->where('id', $id)->first();

        $school = School::where('id', $Son->school_id)->first();

        $distance = getDistance($Son->parents->latitude,
            $Son->parents->longitude, $school->latitude, $school->longitude, 'k');

        $hours = floor($distance / 80);
        $min = ((($distance % 80) * 1.0) / 80) * 60;
        $hours = floor($distance / 80);
        $min = (int)$distance % 60;
        $director = User::where('school_id', $Son->school_id)->first();

        $beginning_of_time = User::where('school_id', $Son->school_id)->value('beginning_of_time');
        $End_of_time = User::where('school_id', $Son->school_id)->value('End_of_time');

        $endTime = strtotime("-" . $min . "minutes", strtotime($beginning_of_time));
        $going = date('h:i', $endTime);

        $endTim = strtotime($min . "minutes", strtotime($End_of_time));
        $return = date('h:i', $endTim);

        if (isset($Son)) {
            $Son->Is_agree = 1;
            $Son->transport_id = $request->no_bus;
            $Son->going = $going;
            $Son->timereturn = $return;
            $Son->update();
            $parent = User::find($Son->parent_id);
            $parent->notify(new \App\Notifications\AcceptanceNotifiction($Son));
            $parent = User::find($Son->parent_id);
            $locale = \App::getLocale();

            if ($locale == 'ar') {
                $message = 'تم قبول طلب التحاق الطالب ' . ' ' . $Son->name;
            } else {
                $message = 'Student' . ' ' . $Son->name . ' ' . 'was accepted';

            }
            $date = Carbon::now();
            $notification = [

                'device_token' => $parent->fcm_token,
                'title' => $date->toDayDateTimeString(),
                'message' => $message,
                'sender' => __('smartbus.school_administration'),

            ];

            pushNotification($notification);


            $success['items'] = $Son;
            return apiSuccess($success, 200, 'smartbus.Student_was_accepted');

        } 
        
        else {
            return apiError('your_son_not_found');

        }

    }


    public function editDisAgreenewstudent(Request $request, $id)
    {
        $Son = Son::where('id', $id)->first();

        if (isset($Son)) {
            $Son->Is_agree = 2;
            $Son->update();
            $parent = User::find($Son->parent_id);
            $parent->notify(new \App\Notifications\NotAcceptanceNotifiction($Son));
            $locale = \App::getLocale();

            if ($locale == 'ar') {
                $message = 'تم رفض طلب التحاق الطالب ' . ' ' . $Son->name;
            } else {
                $message = 'Student' . ' ' . $Son->name . ' ' . 'was Notaccepted';

            }
            $date = Carbon::now();
            $notification = [

                'device_token' => $parent->fcm_token,
                'title' => $date->toDayDateTimeString(),
                'message' => $message,
                'sender' => __('smartbus.school_administration'),

            ];

            pushNotification($notification);
            $success['items'] = $Son;
            return apiSuccess($success, 200, 'smartbus.we_disagree__for_your_son');

        } else {
            return apiError('smartbus.student_not_found');

        }

    }

    public function getAllStudentBus($id)
    {

        $student = Son::with('parents')->where('Is_agree', 1)->where('transport_id', $id)->get();
        $success['items'] = $student;
        return apiSuccess($success);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function EditStudentBus(Request $request, $id)
    {


        $respones = getFirstError($request, [
            'no_bus' => 'required',
        ], [
            'no_bus.required' => 'تنبيه!عليك اختيار باص  ',
        ]);

        if ($respones[IS_ERROR] == true) {
            return apiError($respones[ERROR]);
        }


        $Son = Son::where('id', $id)->first();

        if (isset($Son)) {
            $Son->transport_id = $request->no_bus;
            $Son->update();
            return apiSuccess(null, 200, 'smartbus.Added_succesfully');


        }

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

    public function arrivalcreat($id)
    {
        $Sons = Son::where('Is_agree', 1)->where('id', $id)->first();
        $date = Carbon::now();


        Arrival::create([
            'name_day_ar' => arabicDate($date->englishDayOfWeek),
            'name_day_en' => $date->englishDayOfWeek,
            'going' => $Sons->going,
            'timereturn' => $Sons->timereturn,
            'transport_id' => $Sons->transport_id,
            'son_id' => $Sons->id,
            'date' => $date->toFormattedDateString(),

        ]);
        $date1 = Carbon::tomorrow('Europe/London');

        Arrival::create([
            'name_day_ar' => arabicDate($date1->englishDayOfWeek),
            'name_day_en' => $date1->englishDayOfWeek,
            'going' => $Sons->going,
            'timereturn' => $Sons->timereturn,
            'transport_id' => $Sons->transport_id,
            'son_id' => $Sons->id,
            'date' => $date1->toFormattedDateString(),

        ]);


    }

}
