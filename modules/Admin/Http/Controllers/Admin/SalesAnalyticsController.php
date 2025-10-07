<?php

namespace Modules\Admin\Http\Controllers\Admin;

use Illuminate\Http\Response;
use Modules\Order\Entities\Order;

class SalesAnalyticsController
{
    /**
     * Display a listing of the resource.
     *
     * @param Order $order
     *
     * @return Response
     */
    public function index(Order $order)
    {
        return response()->json([
            'labels' => $this->previousDays(),
            'data' => $order->salesAnalytics(),
        ]);
    }

    private function previousDays()
    {
        $previousDays = array();

        for ($i = 0; $i <= 6; $i++) {
            $weekDay = now()->subDays($i)->weekDay();

            array_unshift($previousDays, trans('admin::dashboard.sales_analytics.day_names')[$weekDay - 1 < 0 ? 6 : $weekDay - 1]);
        }

        return $previousDays;
    }
}
