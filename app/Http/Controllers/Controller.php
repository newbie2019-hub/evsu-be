<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function success($msg): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'msg' => $msg
        ]);
    }
    public function error($msg): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'msg' => $msg
        ], 422);
    }
}
