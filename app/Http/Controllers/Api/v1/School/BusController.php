<?php

namespace App\Http\Controllers\Api\v1\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Eloquent\BusRepository;
use App\Http\Requests\BusRequest;
use App\Models\Bus;
use App\Models\School;
use App\Models\Notification;
use App\User;

use Hash;
use App\Models\transportor;


class BusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $Bus;

    public function __construct(BusRepository $Bus)
    {
        $this->Bus = $Bus;
    }

    public function index($name=null)
    {
        $directore = User::where('id', \Auth::user()->id)->first();
        if ($name != null) {

            $Bu = User::with('transportor')->where('firstname', 'LIKE', '%' . $name . '%')->where('schoobus_id', $directore->id)->get();
            if (count($Bu) > 0) {
                $success['items'] = $Bu;
                return apiSuccess($success);
            } else {
                $Bus = User::with('transportor')->where('schoobus_id', $directore->id)->get();

            }

        }
        else{
            $Bus = User::with('transportor')->where('schoobus_id', $directore->id)->get();
        }
        $success['items'] = $Bus;
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
    public function store(BusRequest $request)
    {   $Bus = new User();
        $Bus->firstname = $request->firstname;
        $Bus->secondname =$request->secondname;
        $Bus->phone_no = $request->phone_no;
        $Bus->password = Hash::make($request->password);
        $Bus->no_bus = $request->no_bus;
        $Bus->role = $request->role;
        $Bus->schoobus_id=\Auth::user()->id;
        $Bus->state = 1;





if(isset($request->image)){
    $ext = pathinfo($request->image->getClientOriginalName(),
             PATHINFO_EXTENSION);

     if ($ext=="png" || $ext=="jpg" || $ext=="gif") {

             $newau= uniqid() . "." . $ext;
             $path = $request->image->move('uploads',$newau);
     }


     if(isset($newau))
         if ($newau != ''  or $newau != null) {
             $Bus->image =$newau;

         }
     }




if(isset($request->Driving_License)){
       $ext = pathinfo($request->Driving_License->getClientOriginalName(),
                PATHINFO_EXTENSION);

        if ($ext=="png" || $ext=="jpg" || $ext=="gif") {

                $new_au= uniqid() . "." . $ext;
                $path = $request->Driving_License->move('uploads',$new_au);
        }


        if(isset($new_au))
            if ($new_au != ''  or $new_au != null) {
                $Bus->Driving_License =$new_au;

            }
        }


        if(isset($request->Certificate_good_conduct)){
            $ext = pathinfo($request->Certificate_good_conduct->getClientOriginalName(),
                     PATHINFO_EXTENSION);

             if ($ext=="png" || $ext=="jpg" || $ext=="gif") {

                     $new= uniqid() . "." . $ext;
                     $path = $request->Certificate_good_conduct->move('uploads',$new);
             }


             if(isset($new))
                 if ($new != ''  or $new != null) {
                     $Bus->Certificate_good_conduct =$new;

                 }
             }
        $Bus->save();

        $tran=transportor::where('id',$request->no_bus)->first();
        $tran->driver_id= $Bus->id;
        $tran->update();
        $url_image = url('/uploads',  $Bus->image);
        $url_Driving_License = url('/uploads',  $Bus->Driving_License);
        $url_Certificate_good_conduct = url('/uploads',  $Bus->Certificate_good_conduct) ;


        $success['items'] = $Bus;
         $success['items']['url_image'] = $url_image;
         $success['items']['url_Driving_License'] = $url_Driving_License;
         $success['items']['url_Certificate_good_conduct'] = $url_Certificate_good_conduct;
        return apiSuccess($success, 200, 'smartbus.Added_succesfully');
    }

//    public function searchbuses(Request $request)
//    {
//
//        if (isset($request->firstname)) {
//            $firstname = $request->firstname;
//            $Bus = User::where('firstname', 'LIKE', '%' . $firstname . '%')->where('role', '2')->where('schoobus_id', \Auth::user()->id)->get();
//            if (count($Bus) > 0) {
//                $success['items'] = $Bus;
//                return apiSuccess($success);
//            } else {
//                return apiSuccess(null, 200, 'smartbus.bus_not_found');
//            }
//        }
//        return apiSuccess(null, 200, 'smartbus.bus_not_found');
//    }
//

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


    public function SendNotificationToDriver(Request $request,$id)
    {
        $respones = getFirstError($request, [
            'message' => 'required',

        ],[]);

        if ($respones[IS_ERROR] == true) {
            return apiError($respones[ERROR]);
        }
        $noti = Notification::create([
            'message_ar' => $request->message,
            'message_en' =>  $request->message,
            'user_id' => auth('api')->user()->id,//sender

        ]);


        $school = School::where('director_id', auth('api')->user()->id);
        $User = User::where('id',$id)->first();
        $notification = [

            'device_token' => $User->fcm_token,
            'title' => $noti->created_at,
            'message' => $request->message,
            'sender' => trans('school_administration'),
            'schoo_id' => $school

        ];

         pushNotification($notification);
        return apiSuccess(null,200,'smartbus.Added_send_succesfully');




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
        $respones = getFirstError($request, [
            'firstname' => 'required|string|min:3|max:100',
            'secondname' => 'required|string|min:3|max:100'
        ],[]);

        if ($respones[IS_ERROR] == true) {
            return apiError($respones[ERROR]);
        }

        $User = User::find(\Auth::user()->id);
        $User->firstname = $request->firstname;
        $User->secondname = $request->secondname;
        $User->update();
        $success['items'] = $User;
        return apiSuccess($success, 200, 'smartbus.Modified_successfully');
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
    public function getDriver($id)
    {
        $driver= User::where('id', $id)->first();
        $success['items'] =  $driver;
        return apiSuccess($success);

    }

}
