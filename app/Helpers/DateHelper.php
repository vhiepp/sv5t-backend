<?php

namespace App\Helpers;

class DateHelper {

    public static function make($date = null) {
        try {    
            $date = strtotime($date);
            return [
                'date' => date('d/m/Y', $date),
                'time' => date('H:i', $date)
            ];

        } catch (\Throwable $th) {
            //throw $th;
            return [
                'date' => null,
                'time' => null
            ];
        }
        
    }

}