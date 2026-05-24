<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\AttendanceSettings;
use Illuminate\Console\Command;

use App\Models\Detail;
use App\Models\Employee;
use DB;
 
use Carbon\Carbon;

class DailyAttend extends Command
{
   /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:attend';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'daily attend abcense employeees on attendance table';

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
     * @return mixed
     */
    public function handle()
    {
        
        $time='';
        if(AttendanceSettings::count() > 0){
            $time = AttendanceSettings::first()->end_permited_attend_time;
            $settingTime = Carbon::parse($time)->format('H:i:s');
            $timeNow = Carbon::now()->format('H:i:s');
            $today = new Carbon();

            if($timeNow > $settingTime && $today->dayOfWeek != Carbon::FRIDAY){
                $dateNow = Carbon::now()->format('Y-m-d');
                $employees = Employee::where('active', 1)->get();
        
                foreach ($employees as $employee) {
                    $empAttend = Attendance::where('date', $dateNow)->where('employee_id', $employee->id)->first();
                    if(!$empAttend){
                        Attendance::create([
                            'date' => $dateNow,
                            'employee_id' => $employee->id,
                            'absence' => 1,
                        ]);
                    }
                }
            }
        }else{
            $time = '13:00';
        }
        

    }
}
