<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;



/*
|--------------------------------------------------------------------------
| Json Format For All Api's
|--------------------------------------------------------------------------
*/
    public function sendResponse($success, $result, $message, $code)
    {
        $response = [
            'success' => $success,
            'message' => $message,
            'data' => $result,
            'code' => $code,
        ];

        return response()->json($response, 200);
    }

// End Function

}
