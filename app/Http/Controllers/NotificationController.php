<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TypeService;

class NotificationController extends Controller
{

    public function getResponse()
    {
        return response()->json($this->response);
    }

    public function sendMail(Request $request)
    {
        return (new TypeService($request))
            ->sendMail()
            ->getResponse();
    }

}
