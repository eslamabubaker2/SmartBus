<?php
namespace App\Repositories\Eloquent;
use App\Repositories\interfaces\SonRepositoryInterface;

use App\Models\Son;
use App\Models\Notification;
use App\Models\School;
use App\User;
use Carbon\Carbon;
use App;



use Illuminate\Http\Request;

class SonRepository implements SonRepositoryInterface
{

    public function store(array $input)
    {

        $Son = new Son();
        $Son->name = $input['name'];
        $Son->gender = $input['gender'];
        $Son->school_id = $input['school_id'];
        $Son->parent_id = auth('api')->user()->id;
        $Son->Is_agree=0;
        $Son->save();
        //nofication  to parents
        $user1=auth('api')->user();
        $user1->notify(new \App\Notifications\NewJoinNotifiction());
        //nofication  to director
        $School = School::where('id', $input['school_id'])->first();
        $user2=User::find($School->director_id);
        $user2->notify(new \App\Notifications\NewJoinDirectoreNotifiction());


        $locale=App::getLocale();
          if ($locale == 'ar') {
           $message = 'شكرا لك!انتظر قبول المدرسة';
           } else {
           $message = 'Thank you! Wait for your son to be admitted to school';

            }
       $date = Carbon::now();
      $notification = [

          'device_token' => $user1->fcm_token,
          'title' =>$date->toDayDateTimeString(),
          'message' => $message,
          'sender' => __('smartbus.school_administration'),

      ];

        pushNotification($notification);

        $locale=App::getLocale();
        if ($locale == 'ar') {
         $message = 'طلب التحاق جديد';

        } else {
         $message = 'New join application';
        }


       $notification = [

           'device_token' => $user2->fcm_token,
           'title' =>$date->toDayDateTimeString(),
           'message' => $message,
           'sender' => '',

       ];
        pushNotification($notification);
        $Son=Son::with('school')->where('Is_agree',0)->where('id',$Son->id)->where('parent_id',auth('api')->user()->id)->get();
        $success['items']=$Son;
        return $Son;


    }


    public function update(array $input, $id)
    {


    }


    public function delete($id)
    {


    }

}


?>
