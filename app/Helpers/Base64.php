<?php

namespace App\Helpers;

class Base64 {

    public static function id_encode($id) {
        $results = base64_encode(json_encode([
            'id' => $id
        ]));
        return $results;
    }

    public static function id_decode($ob) {
        $id = json_decode(base64_decode($ob))->id;
        return $id;
    }

}