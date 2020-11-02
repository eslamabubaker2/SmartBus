<?php

namespace App\Http\Controllers\Api\v1;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\City;
use  DB;

use App;
class CityController extends Controller
{
    /** ProviderRepository.php
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $locale=App::getLocale();

        $city = DB::table('cities')
            ->select('id', 'name_' . $locale . ' as name')
            ->get();

        $success['items'] = $city;

        return response()->json([
            'status' => true,
            'data' =>is_null($success) ? null: $success,
            'message'=> 'Success',
            'satus_code' => 200,

        ],
            200,[],JSON_NUMERIC_CHECK)->header('Content-Type', 'application/json');
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
