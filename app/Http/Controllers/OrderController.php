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
    public function index()
    {
        $orders = Orders::with('pictures')->where('user_id', auth()->id())->get();
        return inertia('Orders/Index', [
            'orders' => $orders
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

    public function edit(Orders $order)
    {
        return inertia('Orders/Edit', compact('order'));
    }

    public function update(Orders $order, updateRequest $request)
    {
        // 1. Обновление основных данных заказа
        $order->update($request->only(['name', 'description']));

        // 2. Обработка изображений с использованием транзакции для откатов в случае ошибок
        try {
            // 2.1 Удаление всех старых изображений
            foreach ($order->pictures as $picture) {  // Получаем все связанные изображения через отношение
                Storage::delete('public/' . $picture->path); // Удаляем файл из хранилища
                $picture->delete(); // Удаляем запись из БД
            }

            // 2.2 Добавление новых изображений
            if ($request->hasFile('pictures')) {
                foreach ($request->file('pictures') as $picture) {
                    $path = $picture->store("orders/{$order->id}", 'public');

                    Pictures::create([
                        'user_id' => auth()->id(),
                        'order_id' => $order->id,
                        'path' => $path,
                    ]);
                }
            }


        } catch (\Exception $e) {
            DB::rollback(); // Откатываем транзакцию в случае ошибки
            // Обработка ошибки (логирование, вывод сообщения пользователю и т.д.)
            \Log::error('Ошибка при обновлении изображений заказа: ' . $e->getMessage());
            return back()->withErrors(['message' => 'Произошла ошибка при обновлении изображений.']); // Возвращаем на предыдущую страницу с сообщением об ошибке
        }

        return redirect()->route('orders.show', $order->id)->with('success', 'Заказ успешно обновлен!'); // Или другой редирект
    }

    public function store(StoreRequest $request)
    {

        $order = Orders::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'description' => $request->description,
        ]);

//        В случае, если нужно распеределить по пользователям, то нужно поменять на $user->id

        if ($request->hasFile('pictures')) {
            foreach ($request->file('pictures') as $picture) {
                $path = $picture->store("orders/{$order->id}", 'public');

                Pictures::create([
                    'user_id' => auth()->id(),
                    'order_id' => $order->id, // Используем созданный заказ
                    'path' => $path
                ]);
            }
        }
//
        return redirect(route('orders')); // Или другой редирект
    }
}
