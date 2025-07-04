<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::post('/telegram-webhook', function (Request $request) {
    $chatId = $request->input('message.from.id');
    $text = $request->input('message.text');

    if (Str::startsWith($text, '/start')) {
        $userId = Str::after($text, '/start ');
        User::where('id', $userId)->update(['telegram_chat_id' => $chatId]);

        // Отправляем подтверждение пользователю
        file_get_contents(
            "https://api.telegram.org/bot".env('TELEGRAM_BOT_API')."/sendMessage?" .
            http_build_query([
                'chat_id' => $chatId,
                'text' => '✅ Ваш аккаунт привязан! Теперь вы будете получать уведомления.'
            ])
        );
    }

    return response()->json(['ok' => true]);
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


