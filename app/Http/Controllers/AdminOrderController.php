<?php

namespace App\Http\Controllers;

use App\Models\Basket;
use App\Models\BasketItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminOrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Basket::with(['user'])
            ->where('status', 'completed')
            ->orderByDesc('created_at');

        if ($request->has('user_id') && !empty($request->user_id)) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $baskets = $query->paginate(20);

        return view('admin.orders.index', [
            'baskets' => $baskets,
        ]);
    }

    public function show(Basket $basket): View
    {
        if ($basket->status !== 'completed') {
            abort(404, 'Заказ не найден!');
        }

        $basket->load(['items.product', 'user']);

        return view('admin.orders.show', [
            'basket' => $basket,
        ]);
    }

    public function destroy(Basket $basket): RedirectResponse
    {
        if ($basket->status !== 'completed') {
            return redirect()
                ->route('admin.orders.index')
                ->with('error', 'Нельзя удалить активную корзину! Можно удалять только завершенные заказы.');
        }

        $basketId = $basket->id;
        $userName = $basket->user ? $basket->user->name : 'Неизвестный пользователь';

        BasketItem::where('basket_id', $basket->id)->delete();

        $basket->delete();

        return redirect()
            ->route('admin.orders.index')
            ->with('success', "Заказ #{$basketId} пользователя '{$userName}' успешно удален!");
    }
}
