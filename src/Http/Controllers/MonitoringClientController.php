<?php

namespace MediactiveDigital\MonitoringClient\Http\Controllers;


class MonitoringClientController
{

    public function get(){

        return response()->json(['ok'=>1]);

    }
}