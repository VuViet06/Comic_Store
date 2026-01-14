<?php

namespace App\Services;

use App\Models\Comic;
use Illuminate\Session\SessionManager;

class CartService
{
    const CART_SESSION_KEY = 'cart';

    protected SessionManager $session;

    public function __construct(SessionManager $session)
    {
        $this->session = $session;
    }

    public function add($comicId, $quantity)
    {
    }

    public function getItems()
    {
    }

    public function updateQuantity($comicId, $newQuantity)
    {
    }

    public function remove($comicId)
    {
    }

    public function clear()
    {
    }

    public function getCount()
    {
    }

    public function getSubtotal()
    {
    }

    public function isEmpty()
    {
    }

    public function getSummary()
    {
    }

    public function validate()
    {
    }
}
