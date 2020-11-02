<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Models\Son;
use Carbon\Carbon;
use App\Models\School;
use App\Models\Arrival;
use App\User;



class arrivedcommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'arrival_commands';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        

        
        $dat1 = Carbon::now();
        $school=User::where('beginning_semester',$dat1->format('Y-m-d'))->get();
          foreach($school as $item){
    
            $son=Son::where('school_id', $item->school_id)->where('Is_agree', 1)->get(); 
            foreach($son as $item){

                $date = Carbon::now();
                Arrival::create([
                    'name_day_ar' => arabicDate($date->englishDayOfWeek),
                    'name_day_en' => $date->englishDayOfWeek,
                    'going' => $item->going,
                    'timereturn' => $item->timereturn,
                    'transport_id' => $item->transport_id,
                    'son_id' => $item->id,
                    'school_id' => $item->school_id,
                    'date' => $date->toFormattedDateString(),
    
                ]);
                $date1 = Carbon::tomorrow('Europe/London');
    
                Arrival::create([
                    'name_day_ar' => arabicDate($date1->englishDayOfWeek),
                    'name_day_en' => $date1->englishDayOfWeek,
                    'going' => $item->going,
                    'timereturn' =>$item->timereturn,
                    'transport_id' => $item->transport_id,
                    'son_id' => $item->id,
                    'school_id' =>$item->school_id,
                    'date' => $date1->toFormattedDateString(),
    
                ]);}
    
        
                }
    }


}
