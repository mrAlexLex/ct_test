<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {
        $service = new PaymentService();
        $service->handle($request->input('uid'), $request->input('sum'), $request->input('method'));
    }
}
