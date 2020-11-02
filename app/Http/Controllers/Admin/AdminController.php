<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
class AdminController extends Controller
{
    public function __construct()
    {
         $this->middleware('auth:admin')->except('ShowformLogin','adminLogin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('cpanel.home');
    }
    public function ShowformLogin()
    {
      return view('cpanel.login');
    }
    public function adminLogin(Request $request)
    {

      $this->validate($request, [
            'email'   => 'required',
            'password' => 'required|min:6'
        ]);



        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {

            return redirect()->intended('admin/index');

        }
        return back()->withErrors(['البريد الالكترونى أو كلمة المرور ليس صحيح']);
    }
    public function logout(Request $request)
    {

        Auth::guard('admin')->logout();

        $request->session()->flush();

        $request->session()->regenerate();

        return redirect()->guest(route( 'admin.login' ));
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
