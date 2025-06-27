<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateRequest;
use App\Models\Orders;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $ordersCount = Orders::count();
        return inertia('Dashboard', [
            'ordersCount' => $ordersCount,
            'is_admin' => true,
        ]);
    }

    public function index()
    {
        $orders = Orders::with('pictures')->with('user')->get();
        return inertia('Orders/Index', [
            'orders' => $orders,
            'is_admin' => true,
        ]);
    }

    public function update(UpdateRequest $request, Orders $order)
    {
        abort_unless(auth()->user()->is_admin, 403);
        $order->update($request->validated());
    }
}
