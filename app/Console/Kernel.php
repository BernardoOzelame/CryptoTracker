<?php 

namespace  App\Console; 

use  Illuminate\Console\Scheduling\Schedule; 
use  Illuminate\Foundation\Console\Kernel  as  ConsoleKernel; 

class  Kernel  extends  ConsoleKernel
 { 
    /** 
     * Defina o agendamento de comandos do aplicativo. 
     */ 
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $userAlerts = \App\Models\Alert::with('user_id')->get();
            \App\Services\CryptoPriceChecker::checkPrices($userAlerts->toArray());
        })->everyFiveMinutes(); // Ajuste o tempo conforme necessário
    }


    /** 
     * Registre os comandos para o aplicativo. 
     */ 
    protected  function  commands ( ): void
     { 
        $this -> load ( __DIR__ . '/Commands' ); 

        require  base_path ( 'routes/web.php' ); 
    } 
}