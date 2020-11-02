<?php

namespace App\Http\Controllers\Api\v1\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\transportor;
use App\Repositories\Eloquent\TransporterRepository;
use App\Http\Requests\TransportRequest;

class transportorController extends Controller
{
    protected $trans;
    public function __construct(TransporterRepository $trans)
    {
        $this->trans = $trans;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($nobus=null)
    {

        if($nobus!=null) {
            $transportor=transportor::with('driver')->withCount('students')->with('students')->where('schoobus_id',auth('api')->user()->id)->where('no_bus', $nobus)->get();

             if (count($transportor) > 0) {
                 $success['items'] = $transportor;
                 return apiSuccess($success);
             }
             else {
                $transportor=transportor::with('driver')->withCount('students')->with('students')->where('schoobus_id',auth('api')->user()->id)->get();

             }
         }
         else{
            $transportor=transportor::with('driver')->withCount('students')->with('students')->where('schoobus_id',auth('api')->user()->id)->get();
        }
        $succcesss['items']= $transportor;
        return apiSuccess($succcesss);
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

    public function store(TransportRequest $request)
    {
        $trans= $this->trans->store($request->all());
        $success['items']=$trans;
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
        $tran = transportor::find($id);
        if(isset($request->no_bus)) {
            $respones = getFirstError($request, [
                'no_bus' => 'min:3|max:3',
            ],[]);

            if ($respones[IS_ERROR] == true) {
                return apiError($respones[ERROR]);
            }

            $tran->no_bus = $request->no_bus;
        }
        if(isset($request->start_latitude)) {
            $tran->start_latitude =$request->start_latitude;
        }
        if(isset($request->start_longitude)) {
            $tran->start_longitude = $request->start_longitude;
        }
        if(isset($request->text_address)) {
            $tran->text_address = $request->text_address;
        }


        $tran->update();
        $success['items']= $tran;
        return apiSuccess($success);
    }


    public function AddNewCoordinateBus(Request $request, $id)
    {
        $tran = transportor::find($id);

        if(isset($request->start_latitude)) {
            $tran->start_latitude =$request->start_latitude;
        }
        if(isset($request->start_longitude)) {
            $tran->start_longitude = $request->start_longitude;
        }

        $tran->update();
        $success['items']= $tran;
        return apiSuccess($success);
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
