<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;

class DashboardController extends Controller
{
    public function index(): Factory|Application|View|\Illuminate\Contracts\Foundation\Application
    {
        return view('backend.dashboard.index')->with([
            'pending_orders' =>  0,
            'processing_orders' => 0,
            'finished_orders' => 0,
            'shipped_orders' => 0,
            'canceled_orders' => 0,
            'refund_orders' => 0,
        ]);
    }
}
