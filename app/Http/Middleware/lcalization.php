<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Application;
use Session;
use App;
class lcalization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }
    public function handle($request, Closure $next)
    {
       // read the language from the request header
        if($request->header('Content-Language')) {
            $locale = $request->header('Content-Language');
            $this->app->setLocale($locale);
}
        elseif(\Session::has('locale')){
            \App::setlocale(\Session::get('locale'));
        }

       // if the header is missed
      else{

           $locale =App::getLocale();
          $this->app->setLocale($locale);
       }



       // set the local language


       // get the response after the request is done
       $response = $next($request);

       // set Content Languages header in the response
       $response->headers->set('Content-Language', $locale);

       // return the response
       return $response;


}
}
