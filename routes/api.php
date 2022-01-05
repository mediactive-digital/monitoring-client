<?php 


Route::middleware(['api',MediactiveDigital\MonitoringClient\Http\Middleware\MonitoringClientSecurity::class])
    ->get( config('monitoring-client.route'), [MediactiveDigital\MonitoringClient\MonitoringClient::class,'get']);


?>