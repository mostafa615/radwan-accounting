<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\AttendanceSettings;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

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
        Log::info('Daily Attend command started.');

        if (AttendanceSettings::exists()) {
            $time = AttendanceSettings::first()->end_permited_attend_time;
            $settingTime = Carbon::parse($time)->format('H:i:s');
            $timeNow = Carbon::now()->format('H:i:s');
            $today = new Carbon();

            Log::info('Attendance settings loaded.', ['setting_time' => $settingTime, 'current_time' => $timeNow]);

            if ($timeNow > $settingTime && $today->dayOfWeek != Carbon::FRIDAY) {
                $dateNow = Carbon::now()->format('Y-m-d');
                $employees = Employee::where('active', 1)->get();

                Log::info('Processing employees.', ['employees_count' => $employees->count(), 'date' => $dateNow]);

                $absenceCount = 0;
                foreach ($employees as $employee) {
                    $empAttend = Attendance::where('date', $dateNow)
                                            ->where('employee_id', $employee->id)
                                            ->first();

                    if (empty($empAttend)) {
                        $attend = new Attendance();
                        $attend->absence = 1;
                        $attend->date = $dateNow;
                        $attend->employee_id = $employee->id;
                        $attend->save();

                        $absenceCount++;
                    }
                }

                Log::info('Daily Attend completed.', ['absence_records_created' => $absenceCount]);
            } else {
                Log::info('DailyAttend skipped.', ['reason' => 'Current time not reached or today is Friday', 'current_time' => $timeNow, 'setting_time' => $settingTime, 'day' => $today->dayOfWeek]);
            }
        } else {
            Log::warning('Attendance settings not found.');
        }

        return 0;
    }
}
