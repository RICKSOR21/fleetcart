<?php

namespace Modules\Cart\Http\Controllers;

use Modules\Cart\Facades\Cart;

class CartController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('storefront::public.cart.index')->with([
            'isCartEmpty' => Cart::isEmpty(),
            'crossSellProducts' => Cart::crossSellProducts()
        ]);
    }


    public function cart()
    {
        return Cart::instance();
    }


    /**
     * Clear the cart.
     *
     * @return \Modules\Cart\Cart
     */
    public function clear()
    {
        Cart::clear();

        return Cart::instance();
    }
}
