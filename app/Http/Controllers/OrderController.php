<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use App\Http\Requests\updateRequest;
use App\Models\Orders;
use App\Models\Pictures;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function dashboard()
    {
        $ordersCount = Orders::where('user_id', auth()->id())->count();
        return inertia('Dashboard', [
            'ordersCount' => $ordersCou
        ]);
    }
    public function index()
    {
        $orders = Orders::with('pictures')->where('user_id', auth()->id())->get();
        $ordersCount = Orders::where('user_id', auth()->id())->count();
        return inertia('Orders/Index', [
            'orders' => $orders,
            'ordersCount' => $ordersCount
        ]);
    }

    public function create()
    {
        return inertia('Orders/Create');
    }

    public function destroy(Orders $order)
    {
        $order->delete();
    }

    public function store(StoreRequest $request)
    {
        $order = Orders::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'description' => $request->description,
            'phone' => str_replace('-', '', $request->phone),
            'tg'=> $request->tg
        ]);

        if ($request->hasFile('pictures')) {
            foreach ($request->file('pictures') as $picture) {
                $path = $picture->store("orders/{$order->id}", 'public');

                Pictures::create([
                    'user_id' => auth()->id(),
                    'order_id' => $order->id,
                    'path' => $path
                ]);
            }
        }
        return redirect(route('orders'));
    }
}
