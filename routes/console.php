<?php

use App\Http\Controllers\ColmeiaController;
use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    /** @var ClosureCommand $this */
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    $teste = new ColmeiaController;

    $teste->transformarDivisaoEmMatriz();
});
// Schedule::call(function () {
//     $teste = new ColmeiaController;

//     $teste->teste();
// })->dailyAt('12:00');