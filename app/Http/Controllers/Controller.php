<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function sendSuccessResponse($message, array $data = [])
    {
        $response =  [
            'success' => true,
            'data'    => $data,
            'message' => $message,
        ];
        return response()->json($response);
    }

    public function sendErrorResponse(array $data = [], $message="Error Response ", $code = 422)
    {
        $response =  [
            'success' => false,
            'errors'    => $data,
            'message' => $message,
        ];
        return response()->json($response, $code);
    }

    public function sendSuccessMessage($message)
    {
        return response()->json([
            'success' => true,
            'message' => $message
        ], 200);
    }

    public function array_flatten($array) 
    {

        $return = array();
        foreach ($array as $key => $value) {
            if (is_array($value)){ $return = array_merge($return, $this->array_flatten($value));}
            else {$return[$key] = $value;}
        }
        return $return;
    }
}
