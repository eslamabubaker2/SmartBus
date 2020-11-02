<?php

namespace App\Http\Controllers\Api\v1\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Kreait\Firebase;

use Kreait\Firebase\Factory;

use Kreait\Firebase\ServiceAccount;

use Kreait\Firebase\Database;



class FirebaseController extends Controller
{

    public function index(){

        $factory = (new Factory)->withServiceAccount(__DIR__.'/smartbusapp-c04f1-firebase-adminsdk-a57xj-d3bcb69224.json');

        $database = $factory->createDatabase();



		$newPost 		  = $database

		                    ->getReference('coordinate')

		                    ->push(['title' => 'Post title','body' => 'This should probably be longer.']);

		echo"<pre>";

		print_r($newPost->getvalue());


    }
}
