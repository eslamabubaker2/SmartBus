<?php

namespace App\Http\Controllers\Api\v1\driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\transportor;
use App\Models\Son;


class studentApiController extends Controller
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
    public function getAllStudentBus($name=null)
    {
        $user = auth('api')->user();

        $tranport=transportor::where('driver_id',$user->id)->first();

        if($name!=null) {
            $user=Son::with('parents')->where('name', 'LIKE', '%' . $name . '%')->where('Is_agree',1)->where('transport_id',$tranport->id)->first();
            if (isset($user)) {
                $success['items'] = $user;
                return apiSuccess($success);
            }
            else {
                return apiSuccess(null,200,'smartbus.not_found');

            }
        }
        else{

            $student =Son::with('parents')
            ->where('Is_agree',1)
            ->where('transport_id',$tranport->id)->get();
           }

        $success['items'] = $student;
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
