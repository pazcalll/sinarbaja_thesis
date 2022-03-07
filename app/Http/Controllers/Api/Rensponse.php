<?php

namespace App\Http\Controllers\Api;

class Response {

    public static function success($message, $data) {
        return response([
            'code'     => 200,
            'message'  => $message,
            'data'     => $data
        ], 200);
    }

    public static function error($message) {
        return response([
            'code'    => 400,
            'message' => $message,
            'data'    => []
        ], 400);
    }
}
