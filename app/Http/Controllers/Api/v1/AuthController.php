<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\School;
use Hash;
use App\Models\transportor;
use App\Models\Son;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase;
use Carbon\Carbon;
use App\Models\Arrival;

use Kreait\Firebase\Factory;

use Kreait\Firebase\ServiceAccount;

use Kreait\Firebase\Database;

class AuthController extends Controller
{

    public function Register(Request $request)
    {

        $respones = getFirstError($request, [
        'firstname' => 'required|min:3',
        'secondname' => 'required|min:3',
        'phone_no' => 'required|' . mobile_regex() . '|unique:users,phone_no',
        'city_id' => 'required|string',
        'password' => password_rules(true),
        'fcm_token' => 'required|string',

    ],[
                'firstname.required' => 'تنبيه!عليك ادخال الاسم الاول ',
                'firstname.min' => 'تنبيه!يجب أن يكون طول النص ليست أقل من 3 حروف  ',
                'secondname.required' => 'تنبيه!عليك ادخال الاسم الثانى',
                'secondname.min' => 'تنبيه!يجب أن يكون طول النص ليست أقل من 3 حروف  ',
                'phone_no.required' => 'تنبيه!عليك ادخال رقم الهاتف ',
                'phone_no.unique' => 'رقم الهاتف مستخدم سابقاَ',
                'city_id.required' => 'تنبيه!عليك اختيار مدينة',
                'fcm_token.required' => 'تنبيه!عليك ادخال fcm_token ',

            ]
            );

        if ($respones[IS_ERROR] == true) {
            return apiError($respones[ERROR]);
        }


        if ($request->role == 1) {
            if ($request->school_id == null) {
                return apiError('smartbus.SelectSchool');
            }
        }


        if (isset($request->phone_no) && strlen($request->phone_no) != 13) return apiError('smartbus.equal_13_digit');


        if (isset($request->school_id)) {
            $respones = getFirstError($request, [
                'beginning_of_time' => 'required|date_format:H:i',
                'End_of_time' => 'required|date_format:H:i',
            ],[
                'beginning_of_time.required' => 'تنبيه!عليك ادخال وقت بداية الدوام ',
                'beginning_of_time.date_format' => 'تنبيه!وقت بداية الدوام لا يتوافق مع الكل التالى 00:00 ',
                'End_of_time.required' => 'تنبيه!عليك ادخال وقت نهاية  الدوام ',
                'End_of_time.date_format' => 'تنبيه!وقت نهاية  الدوام لا يتوافق مع الكل التالى 00:00 ',
               


            ]);
            if ($respones[IS_ERROR] == true) {
                return apiError($respones[ERROR]);
            }

            $arrayDate = explode(":", $request->beginning_of_time);
            if ($arrayDate[0] == '00') {
                return apiError('smartbus.at_least_hour');
            }

            $school = School::where('id', $request->school_id)->where('phone_no', $request->phone_no)->first();

            if (!isset($school))
                return apiError('smartbus.NotMatchSchool');


        }

        $code = generateCode();
        $createdShow = User::create([
            'firstname' => $request->firstname,
            'secondname' => $request->secondname,
            'phone_no' => $request->phone_no,
            'password' => Hash::make($request->password),
            'city_id' => $request->city_id,
            'code' => $code,
            'state' => 0,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'role' => $request->role,
            'fcm_token' => $request->fcm_token,
            'beginning_of_time' => $request->beginning_of_time,
            'End_of_time' => $request->End_of_time,
            'school_id' => $request->school_id,
            'image' => 'user.png',


        ]);
        if (isset($request->school_id)) {
            $school = School::where('id', $request->school_id)->where('phone_no', $request->phone_no)->first();
            $school->director_id = $createdShow->id;
            $school->beginning_of_time = $request->beginning_of_time;
            $school->latitude = $request->latitude;
            $school->longitude = $request->longitude;
            $school->End_of_time = $request->End_of_time;
            $school->update();

        }


//        TODO check if the sms message is sanded successfully or not
        // codeSMS("your code is: $code", $request->phone_no);
        $token = $createdShow->createToken('MyApp')->accessToken;
        // $lastpath_image = url('/uploads', $createdShow->image);
        $success['items'] = $createdShow;
        $success['items']['token'] = $token;


        return apiSuccess($success);

    }


    public function login(Request $request)
    {
        if (!isset($request->phone_no)) return apiError('smartbus.phone_is_required');
        if (!isset($request->password)) return apiError('smartbus.password_is_required');


        if (\Auth::attempt(['phone_no' => request('phone_no'), 'password' => request('password')])) {
            $user = \Auth::user();


            if ($user->state == 0) {
                // $code = generateCode();
                // $user->code=$code;
                // $user->update();
                $success['items'] = $user;

                return apiSuccess($success, 200, 'smartbus.your_account_not_active');
            }
            else {
                $success['items'] = $user;
                $success['items']['token'] = $user->createToken('MyApp')->accessToken;
                return apiSuccess($success);
            }

        } else {
            return apiError('smartbus.usernotfound');
        }
    }


    public function Activateuser(Request $request)
    {

        $respones = getFirstError($request, [
            'code' => 'required|min:4|max:4',
            'phone_no' => 'required|' . mobile_regex(),
        ],[
            'code.required' => 'تنبيه!عليك ادخال  الكود الصحيح  ',
            'code.min' => ' تنبيه!يجب أن يكون طول النص 4 خانات ',
            'phone_no.required' => 'تنبيه!عليك ادخال  رقم الهاتف المسجل لديك  ',
        ]);
        if ($respones[IS_ERROR] == true) {
            return apiError($respones[ERROR]);
        }
        $user = User::where('phone_no', $request->phone_no)->first();
        if (isset($user)) {
            if ($user->code != $request->code) return apiError('smartbus.code_is_wrong');
            $user->update([
                'state' => 1
            ]);

            $token = $user->createToken('MyApp')->accessToken;
            $success['items'] = $user;
            $success['items']['token'] = $token;
            return apiSuccess($success, 200, 'smartbus.code_confirmed_successfully');
        }
        else {
            return apiError('smartbus.account_not_found');
        }
    }


    public function ForgetPassword(Request $request)
    {
        if (!isset($request->phone_no)) return apiError('smartbus.phone_is_required');

        $user = User::where('phone_no', $request->phone_no)->first();

        if (isset($user)) {
            $code = generateCode();
            $user->code = $code;
            $user->state = 0;
            $user->update();
            $success['items'] = $user;
            return apiSuccess($success, 200, 'smartbus.dOne_active_code');

        } else {
            return apiError('smartbus.account_not_found');
        }


    }


    public function adressuser(Request $request)
    {
        if (!isset($request->latitude)) return apiError('smartbus.latitude_is_required');
        if (!isset($request->longitude)) return apiError('smartbus.longitude_is_required');
        if (!isset($request->text_adress)) return apiError('smartbus.text_adress_is_required');


        $user = auth('api')->user();


        if (isset($user)) {
            $user->latitude = $request->latitude;
            $user->longitude = $request->longitude;
            $user->text_adress = $request->text_adress;
            $user->update();
            $success['items'] = $user;
            return apiSuccess($success, 200, 'smartbus.adress_successfully');

        } else {
            return apiError('samrtbus.Unautherized');
        }


    }


    public function changepassword(Request $request)
    {

        $respones = getFirstError($request, [

            'password' =>'required|'. password_rules(true),
            'password_confirmation' => 'required_with:password|same:password',
            'code' => 'required|string|min:4|max:4',
            'phone_no' => 'required|' . mobile_regex(),


        ],[
            'password.required' => 'عليك ادخال كلمة المرور ',
            'password_confirmation.same' => 'كلمة المرور غير مطابقة',
            'code.required' => 'عليك ادخال الكود ',
            'phone_no.required' => 'عليك ادخال رقم الهاتف',
        ]);

        if ($respones[IS_ERROR] == true) {
            return apiError($respones[ERROR]);
        }


        $user = User::where('phone_no', $request->phone_no)->first();


        if ($user->code != $request->code) return apiError('smartbus.code_is_wrong');
        $user->update([
            'state' => 1,
            'password' => Hash::make($request->password),


        ]);
        return apiSuccess(null, 200, 'smartbus.your_password_changed_successfully');


    }


    public function AnotherSendCode(Request $request)
    {

        if (!isset($request->phone_no)) return apiError('smartbus.phone_is_required');

        $user = User::where('phone_no', $request->phone_no)->first();

        if (isset($user)) {
            $code = generateCode();
            $user->code = $code;
            $user->state = 0;
            $user->update();
            $success['items'] = $user;
            return apiSuccess($success, 200, 'smartbus.dOne_active_code');

        }
        else {
            return apiError('smartbus.account_not_found');
        }
    }

    public function changePasswordd(Request $request,$id=null)
    {
        $respones = getFirstError($request, [
            'oldpassword' => 'required',
            'password' =>'required|' .password_rules(true),
            'password_confirmation' => 'required_with:password|same:password',


        ],[
            'oldpassword.required' => ' عليك ادخال كلمة المرور القديمة',
            'password.required' => 'عليك ادخال كلمة المرور',
            'code.required' => 'عليك ادخال الكود ',
            'password_confirmation.required' => 'عليك ادخال تأكيد كلمة المرور',

        ]);

        if ($respones[IS_ERROR] == true) {
            return apiError($respones[ERROR]);
        }


        if($id!=null){
            $User = User::find($id);
        }
        else{
            $User = User::find(\Auth::user()->id);
        }
        if (!(Hash::check($request->oldpassword, $User->password))) {
            return apiError('smartbus.NotMatchpassword');
        } else {
            $User->password = Hash::make($request->password);
            $User->update();
            $success['items'] = $User;
            return apiSuccess($success, 200, 'smartbus.Modified_successfully');
        }
    }

    public function changemobileno(Request $request)
    {
        $respones = getFirstError($request, [
            'phone_no' => 'required|' . mobile_regex() . '|unique:users,phone_no',
        ],[
            'phone_no.required' => ' عليك ادخال رقم الهاتف',
            'phone_no.unique' => ' رقم الهاتف مسجل لدينا مسبقا',
        ]);

        if ($respones[IS_ERROR] == true) {
            return apiError($respones[ERROR]);
        }

        $code = generateCode();

        $User = User::find(\Auth::user()->id);
        $User->code = $code;
        $User->phone_no = $request->phone_no;
        $User->state = 0;
        $User->update();

        $success['items'] = $User;
        return apiSuccess($success, 200, 'smartbus.dOne_active_code');

    }


    public function Editinfo(Request $request)
    {

        $user = \Auth::user();
        if (isset($request->firstname)) {

            $user->firstname = $request->firstname;
        }


        if (isset($request->secondname)) {
            $user->secondname = $request->secondname;
        }
        if (isset($request->city_id)) {

            $user->city_id = $request->city_id;
        }

        $Sons = Son::where('Is_agree', 1)->where('school_id', auth('api')->user()->school_id)->get();
        if (isset($request->beginning_of_time)) {
            $respones = getFirstError($request, [
                'beginning_of_time' => 'date_format:H:i',
            ],[
                'beginning_of_time.required' => ' عليك ادخال الوقت وفقا للشكل التالى:00:00',
            ]);
            if ($respones[IS_ERROR] == true) {
                return apiError($respones[ERROR]);
            }
            $user->beginning_of_time = $request->beginning_of_time;
            if(count($Sons)>0){
            foreach ($Sons as $item) {

                $endTime = strtotime("-15 minutes", strtotime($request->beginning_of_time));
                $going = date('h:i', $endTime);
                $Sons->going = $going;
                $item->update();

            }}

        }

        if (isset($request->End_of_time)) {
            $respones = getFirstError($request, [
                'End_of_time' => 'date_format:H:i',
            ],[
                'End_of_time.required' => ' عليك ادخال الوقت وفقا للشكل التالى:00:00',

            ]);

            if ($respones[IS_ERROR] == true) {
                return apiError($respones[ERROR]);
            }

            $user->End_of_time = $request->End_of_time;
            if(count($Sons)>0){
            foreach ($Sons as $item) {
                $endTim = strtotime("+15 minutes", strtotime($request->End_of_time));
                $return = date('h:i', $endTim);
                $Sons->return = $return;
                $item->update();

            }}
        }


        if (isset($request->beginning_semester)) {
            $respones = getFirstError($request, [
                'beginning_semester' => 'required|date_format:Y-m-d',
            ],[
                'beginning_semester.required' => 'تنبيه!عليك ادخال بداية الدوام الدراسى    ',
                'beginning_semester.date_format' => ' تنبيه!عليك ادخال بداية الدوام الدراسى على الشكل التالى y-m-d   ',

            ]);

            if ($respones[IS_ERROR] == true) {
                return apiError($respones[ERROR]);
            }

            $user->beginning_semester = $request->beginning_semester;


        }
        

        if (isset($request->image)) {

            $ext = pathinfo($request->image->getClientOriginalName(),
                PATHINFO_EXTENSION);

            if ($ext == "png" || $ext == "jpg" || $ext == "gif") {

                $new_au = uniqid() . "." . $ext;
                $path = $request->image->move('uploads', $new_au);
            }


            if (isset($new_au))
                if ($new_au != '' or $new_au != null) {
                    $user->image = $new_au;
                }
        }



        $user->update();

        $lastpath = url('/uploads', $user->image);
        $success['items'] = $user;
        $success['items']['url_image'] = $lastpath;

        return apiSuccess($success, 200, 'smartbus.Modified_successfully');

    }

    public function Editinfodriver($idschooleditdriver){
       $user= User::where('id',$idschooleditdriver);
        if (isset($request->firstname)) {

            $user->firstname = $request->firstname;
        }
        if (isset($request->secondname)) {
            $user->secondname = $request->secondname;
        }
        if (isset($request->no_bus)) {
            $oldbus = $user->no_bus;
            $trans = transportor::where('id', $oldbus)->first();
            $trans->driver_id = null;
            $trans->update();


            $transpnew = transportor::where('id', $request->no_bus)->first();
            $transpnew->driver_id = $idschooleditdriver;
            $user->no_bus = $request->no_bus;
        }

        if (isset($request->image)) {

            $ext = pathinfo($request->image->getClientOriginalName(),
                PATHINFO_EXTENSION);

            if ($ext == "png" || $ext == "jpg" || $ext == "gif") {

                $new = uniqid() . "." . $ext;
                $path = $request->image->move('uploads', $new);
            }


            if (isset($new))
                if ($new != '' or $new!= null) {
                    $user->image = $new;
                }
        }


        $user->update();
        $lastpath = url('/uploads', $user->image);
        $success['items'] = $user;
        $success['items']['url_image'] = $lastpath;

        return apiSuccess($success, 200, 'smartbus.Modified_successfully');
    }
    public function allNotification()
    {
        $locale = \App::getLocale();

       $notification = \Auth::user()->unreadNotifications()->select('id','data->title as title','data->message_'.$locale.' as message','data->sender_id as sender_id')->orderBy('id','desc')->paginate(3);
      $success['items']=$notification;
      return apiSuccess($success);
    }
    public function removecoordinate($idbus)
    {

        if (!isset($request->move_latitude)) return apiError('smartbus.latitude_is_required');
        if (!isset($request->move_longitude)) return apiError('smartbus.longitude_is_required');
        $tran=transportor::where('id',$idbus)->first();
        $tran->move_latitude=$request->move_latitude;
        $tran->move_longitude=$request->move_longitude;
        $tran->update();
        
        $factory = (new Factory)->withServiceAccount(__DIR__.'/smartbusapp-c04f1-firebase-adminsdk-a57xj-d3bcb69224.json');

        $database = $factory->createDatabase();

       $ref= $database->getReference('location');
      dd($ref->push->getKye());




      $success['items']= $tran;
      return apiSuccess(null,200,'Done');
    }


}
