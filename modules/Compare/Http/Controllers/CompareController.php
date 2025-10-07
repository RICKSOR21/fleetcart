<?php

namespace Modules\Compare\Http\Controllers;

use Modules\Compare\Compare;
use Illuminate\Http\Response;

class CompareController
{
    /**
     * Display a listing of the resource.
     *
     * @param Compare $compare
     *
     * @return Response
     */
    public function index(Compare $compare)
    {
        return view('storefront::public.compare.index', [
            'compare' => $compare
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Compare $compare
     *
     * @return Response
     */
    public function store(Compare $compare)
    {
        $compare->store(request('productId'));
    }


    public function list(Compare $compare)
    {
        return $compare->list();
    }


    public function products(Compare $compare)
    {
        return response()->json([
            'products' => $compare->products(),
            'attributes' => $compare->attributes()
        ]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $productId
     * @param Compare $compare
     *
     * @return Response
     */
    public function destroy($productId, Compare $compare)
    {
        $compare->remove($productId);
    }
}
