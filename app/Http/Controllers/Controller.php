<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function resSuccessJson($result = [], $message = 'Success')
    {
        $resp['code']       = 200;
        $resp['message']    = $message;
        $resp['result']     = $result;
        return response()->json($resp, 200);
    }

    public function resErrorJson($result = [], $message = 'Error', $code = 500)
    {
        $resp['code']       = $code;
        $resp['message']    = $message;
        $resp['result']     = $result;
        return response()->json($resp, $code);
    }
}
