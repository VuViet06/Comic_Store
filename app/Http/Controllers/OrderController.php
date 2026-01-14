<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function myOrders(Request $request)
    {
    }

    public function show($code)
    {
    }

    public function track(Request $request)
    {
    }

    public function requestCancel($code)
    {
    }

    public function requestReturn($code)
    {
    }

    public function getStatus(Request $request)
    {
    }
}
