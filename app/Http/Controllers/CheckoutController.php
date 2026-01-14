<?php

namespace App\Http\Controllers;

use App\Services\CheckoutService;
use App\Services\OrderService;
use App\Http\Requests\CheckoutRequest;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    protected CheckoutService $checkoutService;
    protected OrderService $orderService;

    public function __construct(
        CheckoutService $checkoutService,
        OrderService $orderService
    ) {
        $this->checkoutService = $checkoutService;
        $this->orderService = $orderService;
    }

    public function show(Request $request)
    {
    }

    public function process(CheckoutRequest $request)
    {
    }

    public function applyVoucher(Request $request)
    {
    }

    public function removeVoucher(Request $request)
    {
    }

    public function success(Request $request)
    {
    }

    public function preview(Request $request)
    {
    }
}
