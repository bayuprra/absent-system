<?php

namespace App\Console;

use App\Models\AbsenTime;
use App\Models\Karyawan;
use App\Models\UserAbsent as ModelsUserAbsent;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            $data = new AbsenTime();
            $userAbsent = Karyawan::all();
            $data->tanggal = now()->toDateString();
            if (!AbsenTime::where('tanggal', $data->tanggal)->exists()) {
                if (now()->isWeekend()) {
                    $data->status = 1;
                }
                $data->save();
                $userAbsentData = [];
                foreach ($userAbsent as $ua) {
                    $userAbsentData[] = [
                        'karyawan_id' => $ua->id,
                        'absenttime_id' => $data->id
                    ];
                }
                ModelsUserAbsent::insert($userAbsentData);
            }
        })->everyMinute()->runInBackground();
        // })->weekdays()->at('00:01')->runInBackground();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
