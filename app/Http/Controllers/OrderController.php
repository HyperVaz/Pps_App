<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequest;
use App\Http\Requests\updateRequest;
use App\Models\Orders;
use App\Models\Pictures;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    public function dashboard()
    {
        $ordersCount = Orders::where('user_id', auth()->id())->count();
        return inertia('Dashboard', [
            'ordersCount' => $ordersCount
        ]);
    }

    public function index()
    {
        $orders = Orders::with('pictures')->where('user_id', auth()->id())->get();
        $ordersCount = Orders::where('user_id', auth()->id())->count();
        return inertia('Orders/Index', [
            'orders' => $orders,
            'ordersCount' => $ordersCount,
            'user' => auth()->user(),
            'telegram_bot_key' => env('TELEGRAM_BOT_API'),
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
            'tg' => $request->tg
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
        if (auth()->user()->is_admin) {
            return redirect(route('admin.orders'));
        }
        $phoneMessage = $order->phone ? $order->phone : 'Пользователь не указал свой номер телефона :(';
        $message = "🎉 *Новый заказ!* 🎉

👤 *Клиент:* {$order->user->name}
📌 *Услуга:* {$order->name}
📝 *Описание:*
{$order->description}

📱 *Контакты:*
- ☎️ Телефон: {$phoneMessage}
- ✈️ Telegram: " . ($order->tg ? "@" . ltrim($order->tg, '@') : 'не указан') . "\n" .
            "Подробнее:" . "http://127.0.0.1:8000/orders";
        Http::post('https://api.telegram.org/bot'.config('telegram.bot_token').'/sendMessage', [
            'chat_id' => env('ADMIN_CHAT_ID'),
            'text' => $message,
            'parse_mode' => 'Markdown',
            'disable_web_page_preview' => true
        ]);
        return redirect(route('orders'))->with('success', 'Заявка создана! С вами свяжется администратор в ближайшее время для уточнения деталей заказа.');
    }
}
