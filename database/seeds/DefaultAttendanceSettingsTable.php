<?php

use Illuminate\Database\Seeder;

use App\Models\DefaultAttendanceSetting;
use Carbon\Carbon;
class DefaultAttendanceSettingsTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DefaultAttendanceSetting::create([
            'holidaies_balance'=>'24',
            'year'=>Carbon::now()->year,
        ]);
    }
}
