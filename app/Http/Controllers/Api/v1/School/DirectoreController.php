<?php

namespace App\Http\Controllers\Api\v1\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Models\Son;

use Hash;


class DirectoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function update(Request $request)
    {




    }




    public function changemobileno(Request $request,$id)
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

        $User = User::find($id);
        $User->code = $code;
        $User->phone_no = $request->phone_no;
        $User->state = 0;
        $User->update();

        $success['items'] = $User;
        return apiSuccess($success, 200, 'smartbus.dOne_active_code');

    }




    public function Editinfodriver(Request $request,$id)
    {


        $User = User::find($id);

        if (isset($request->firstname)) {
            $respones = getFirstError($request, [
                'firstname' => 'string|min:3',

            ],[
                'firstname.required' => ' عليك ادخال الاسم الأول',
                 'firstname.min' => ' طول الاسم  أقل من 3 خانات',
            ]);

            if ($respones[IS_ERROR] == true) {
                return apiError($respones[ERROR]);
            }
            $User->firstname = $request->firstname;
        }


        if (isset($request->secondname)) {
            $respones = getFirstError($request, [
                'secondname' => 'string|min:3'
            ],[
                'secondname.required' => ' عليك ادخال الاسم الثانى',
                 'secondname.min' => ' طول الاسم  أقل من 3 خانات',
            ]);

            if ($respones[IS_ERROR] == true) {
                return apiError($respones[ERROR]);
            }
            $User->secondname = $request->secondname;
        }



        if (isset($request->no_bus)) {
            $User->no_bus = $request->no_bus;
        }



        if(isset($request->image)){

            $ext = pathinfo($request->image->getClientOriginalName(),
                PATHINFO_EXTENSION);

            if ($ext == "png" || $ext == "jpg" || $ext == "gif") {

                $new_au = uniqid() . "." . $ext;
                $path = $request->image->move('uploads', $new_au);
            }


            if (isset($new_au))
                if ($new_au != '' or $new_au != null) {
                    $User->image =$new_au;
                }}
        $User->save();
        $lastpath= url('/uploads',  $User->image);

        $success['items'] = $User;
        $success['items']['url_image']=$lastpath;

        return apiSuccess($success, 200, 'smartbus.Modified_successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */





    public function EditAttachment(Request $request,$id)
    {
          $User = User::find($id);


if(isset($request->Driving_License)){
    $ext = pathinfo($request->Driving_License->getClientOriginalName(),
             PATHINFO_EXTENSION);

     if ($ext=="png" || $ext=="jpg" || $ext=="gif") {

             $new_au= uniqid() . "." . $ext;
             $path = $request->Driving_License->move('uploads',$new_au);
     }


     if(isset($new_au))
         if ($new_au != ''  or $new_au != null) {
            $User->Driving_License =$new_au;

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
                $User->Certificate_good_conduct =$new;

              }
          }

        $User->update();

        $url_Driving_License = url('/uploads',  $User->Driving_License);
        $url_Certificate_good_conduct = url('/uploads',  $User->Certificate_good_conduct) ;


         $success['items'] = $User;
         $success['items']['url_Driving_License'] = $url_Driving_License;
         $success['items']['url_Certificate_good_conduct'] = $url_Certificate_good_conduct;

        return apiSuccess($success, 200, 'smartbus.Modified_successfully');

    }
    public function destroy($id)
    {
       Son::where('id',$id)->delete();
       return apiSuccess(null, 200, 'smartbus.removed_successfully');

    }
}
