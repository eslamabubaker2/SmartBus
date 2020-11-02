<?php
namespace App\Repositories\Eloquent;
use App\Repositories\interfaces\TransportorsInterface;

use App\Models\transportor;
use App\Models\Notification;
use App\Models\School;
use App\User;



use Illuminate\Http\Request;

class TransporterRepository implements TransportorsInterface
{


    public function store(array $input)
    {

        $tran = new transportor();
        $tran->no_bus = $input['no_bus'];
        $tran->start_latitude = $input['start_latitude'];
        $tran->start_longitude = $input['start_longitude'];
        $tran->schoobus_id = auth('api')->user()->id;
        $tran->text_address = $input['text_address'];

        $tran->save();
        return  $tran;




    }


    public function update(array $input, $id)
    {

    }


    public function delete($id)
    {


    }

}


?>
