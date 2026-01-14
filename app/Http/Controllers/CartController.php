<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index(Request $request)
    {
    }

    public function add(Request $request)
    {
    }

    public function update(Request $request)
    {
    }

    public function remove(Request $request)
    {
    }

    public function clear()
    {
    }

    public function getCount()
    {
    }

    public function getMiniCart()
    {
    }
}
