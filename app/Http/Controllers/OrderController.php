<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use App\Models\Orders;
use http\Client\Curl\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Orders::where('user_id', auth()->id())->get();
        return inertia('Orders/Index', compact('orders'));
    }

    public function create()
    {
        return inertia('Orders/Create');
    }
    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        Orders::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'description' => $request->description,
        ]);
    }
}
