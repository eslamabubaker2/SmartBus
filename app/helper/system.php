<?php
function cpanel_layout(){
    return 'cpanel.layout';
}

define('IS_ERROR', 'isError');
define('ERRORS', 'errors');
define('ERROR', 'error');


if (!function_exists('apiSuccess')) {
    function apiSuccess($data = null, $status = 200,$message = null)
    {
        return response()->json([
            'status' => true,
            'data' =>is_null($data) ? null: $data,
            'message'=>  is_null($message) ? 'Success': trans($message),
            'satus_code' => $status,

        ],
            $status)->header('Content-Type', 'application/json');
    }
}


if (!function_exists('apiError')) {
    function apiError($message, $status = 400)
    {
        $messageCount = 1;
// var_dump($message);
        if (is_array($message)) {
            $messageCount = sizeof($message);
        } elseif ($message instanceof Collection) {
            $messageCount = $message->count();
        }

        if ($message instanceof MessageBag) {
            $message = $message->first();
        }
        return response()->json(
            [
            'status' => false,
            'data' =>null,
            'message' => trans($message),
            'satus_code' => $status,], $status)
            ->header('Content-Type', 'application/json');
    }
}



if (!function_exists('mobile_regex')) {
    function mobile_regex($typeOf = 'string')
    {
        if ($typeOf == 'array')
            return ['regex:/^([0-9\s\-\+\(\)]*)$/'];
        return 'regex:/^([0-9\s\-\+\(\)]*)$/';
    }
}


if (!function_exists('password_rules')) {
    function password_rules($required = false, $min = '8', $confirmed = false)
    {
        $rules = [
            $required ? 'required' : 'nullable',
            'string',
            'min:' . $min
        ];
        return $confirmed ? array_merge($rules, ['confirmed']) : $rules;
    }
}

if (!function_exists('getFirstError')) {
    function getFirstError($request, $validations,$customMessages)
    {
        $response = customeValidation($request, $validations,$customMessages);
        if ($response[IS_ERROR] == true) {
            $response[ERROR] = $response[ERRORS][0];
            return $response;
        }
        return $response;

    }
}



if (!function_exists('apiTransErrors')) {
    function apiTransErrors($error, $transParams = [])
    {
        return trans('messages.api.errors.' . $error, $transParams);
    }
}



if (!function_exists('generateCode')) {
    function generateCode($min = 0, $max = 9, $quantity = 4)
    {
        $numbers = range($min, $max);
        shuffle($numbers);
        return implode(array_slice($numbers, 0, $quantity));
    }
}



if (!function_exists('customeValidation')) {
    function customeValidation($request, $validations,$customMessages)
    {
        $validator = Validator::make($request->all(), $validations,$customMessages);
        if ($validator->fails()) {
            $err = array();
            foreach ($validator->errors()->toArray() as $index => $error) {
                foreach ($error as $index2 => $sub_error) {
                    array_push($err, $sub_error);
                }
            }
            return [
                IS_ERROR => true,
                ERRORS => $err,
            ];
        }


        return [
            IS_ERROR => false,
            ERRORS => [],
        ];

    }
}


// if (!function_exists('codeSMS')) {
//     function codeSMS($msg, $phone)
//     {
// //        $phone = TEST_SMS;
//         $client = new Client();
//         return $client->request('GET', "https://www.ismartsms.net/iBulkSMS/HttpWS/SMSDynamicAPI.aspx?UserId=md_webser&Password=Mdealer@789&MobileNo=" . $phone . "&Message=" . $msg . "&Lang=0&FLashSMS=N");
//     }
// }





function pushNotification($notification)
{

      $token = $notification['device_token'];


$API_ACCESS_KEY1='AAAACjIXkzo:APA91bGg6CaXisjRh-e5NV41P8iou9bPW5znhLPr0RqXepoEc90gAJeyby2Wi3NotH0CoiSWkLbmBg05OKD0zSurhiW52JKN4_3c8xL8zi0AmqFO9dHfJb79DzNt22PAdAOVps368l_t';
$msg = array
(   'title'	=> $notification['title'],
    'body' 	=>  $notification['message'],
    'sender'=>$notification['sender'],

);
$fields = array
(
    'to'=>$token  ,
    'notification'=> $msg
);



$notification['content_available'] = true;
$header = ['Authorization: Key=' . $API_ACCESS_KEY1, 'Content-Type: Application/json'];
$tokens[] = $token;

$payload = ['registration_ids' => $tokens,
        'notification' => $notification,
//            data message
        'data' => $notification];


// $payload = ['registration_ids' => $tokens, 'data' => $msg];
$curl = curl_init();
curl_setopt_array($curl, array(
CURLOPT_URL => "https://fcm.googleapis.com/fcm/send",
CURLOPT_RETURNTRANSFER => true,
CURLOPT_CUSTOMREQUEST => "POST",
CURLOPT_POSTFIELDS => json_encode($payload),
CURLOPT_HTTPHEADER => $header
));
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
return response()->json([
'firebase_response' => json_decode($response)
],200);




}
function SendNotification()
{
$tokens = \App\User::get();
$fcm_ids = array();
foreach ($tokens as $token) {
    $fcm_ids[] = $token['fcm_token'];
}

define( 'API_ACCESS_KEY', 'AAAACjIXkzo:APA91bGg6CaXisjRh-e5NV41P8iou9bPW5znhLPr0RqXepoEc90gAJeyby2Wi3NotH0CoiSWkLbmBg05OKD0zSurhiW52JKN4_3c8xL8zi0AmqFO9dHfJb79DzNt22PAdAOVps368l_t');
$msg = array
(
    'body' 	=> 'alaa neww foor all user ',
    'title'	=>'تطبيق ',
);
$fields = array
(
    'registration_ids'=>$fcm_ids,
    'notification'=> $msg
);

$headers = array
(
    'Authorization: key=' . API_ACCESS_KEY,
    'Content-Type: application/json'
);
$ch = curl_init();
curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
curl_setopt( $ch,CURLOPT_POST, true );
curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );

$response = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);
return response()->json([
'firebase_response' => json_decode($response)
],200);


}

function arabicDate($dat){
    switch ($dat){
        case 'Saturday' : return "السبت";
        case 'Sunday' : return "الأحد";
        case 'Monday' : return "الاثنين";
        case 'Tuesday' : return "الثلاثاء";
        case 'Wednesday' : return "الاربعاء";
        case 'Thursday' : return "الخميس";
        case 'Friday' : return "الجمعة";

    }
}
function getDistance($latitudeFrom , $longitudeFrom, $latitudeTo ,$longitudeTo, $unit){
    // Google API key
    $apiKey = 'AIzaSyAU4Zxi-MPG9HSJJUX6bJCC0XPVgWKh1vs';

    // Calculate distance between latitude and longitude
    $theta    = $longitudeFrom - $longitudeTo;
    $dist    = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
    $dist    = acos($dist);
    $dist    = rad2deg($dist);
    $miles    = $dist * 60 * 1.1515;

    // Convert unit and return distance
    $unit = strtoupper($unit);
    if($unit == "K"){
        return round($miles * 1.609344, 2);
    }
}
?>
