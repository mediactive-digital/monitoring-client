<?php 


Route::middleware('MonitoringClient')->get( config('monitoring-client.route'), function (Request $request) {
    return 'ok';
});


?>