<?php

namespace App\Http\Controllers;

use App\Events\BasketCompletedEvent;
use App\Http\Requests\Basket\AddToBasketRequest;
use App\Http\Requests\Basket\UpdateBasketItemRequest;
use App\Http\Requests\Basket\CheckoutRequest;
use App\Models\Basket;
use App\Models\BasketItem;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Events\BasketUpdateEvent;

class BasketController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $activeBasket = $user->activeBasket();

        if ($activeBasket) {
            $activeBasket->load(['items.product']);
        }

        $totalSum = $activeBasket ? $activeBasket->totalSum() : 0;
        $totalItems = $activeBasket ? $activeBasket->totalItems() : 0;

        return view('basket.index', [
            'basket' => $activeBasket,
            'totalSum' => $totalSum,
            'totalItems' => $totalItems,
        ]);
    }

    public function add(AddToBasketRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $items = $request->input('items');

        $activeBasket = $user->activeBasket();

        if (!$activeBasket) {
            $activeBasket = Basket::create([
                'user_id' => $user->id,
                'status' => 'pending',
            ]);
        }

        foreach ($items as $itemData) {
            $product = Product::find($itemData['product_id']);

            if (!$product) {
                return redirect()
                    ->route('basket.index')
                    ->with('error', 'Товар не найден!');
            }

            if (!$product->hasStock($itemData['quantity'])) {
                return redirect()
                    ->route('basket.index')
                    ->with('error', "Недостаточно товара '{$product->name}' на складе! Доступно: {$product->stock_quantity}");
            }

            $basketItem = BasketItem::where('basket_id', $activeBasket->id)
                ->where('product_id', $product->id)
                ->first();

            if ($basketItem) {
                $basketItem->quantity += $itemData['quantity'];
                $basketItem->save();
            } else {
                BasketItem::create([
                    'basket_id' => $activeBasket->id,
                    'product_id' => $product->id,
                    'quantity' => $itemData['quantity'],
                ]);
            }
        }

        $activeBasket->load(['items.product']);

        broadcast(new BasketUpdateEvent(
            userId: $user->id,
            totalItems: $activeBasket->totalItems(),
            totalSum: $activeBasket->totalSum()
        ));

        return redirect()
            ->route('basket.index')
            ->with('success', 'Товары успешно добавлены в корзину!');
    }

    public function update(UpdateBasketItemRequest $request, $itemId): RedirectResponse
    {
        $user = Auth::user();
        $newQuantity = $request->input('quantity');

        $activeBasket = $user->activeBasket();

        if (!$activeBasket) {
            return redirect()
                ->route('basket.index')
                ->with('error', 'Корзина не найдена!');
        }

        $basketItem = BasketItem::where('id', $itemId)
            ->where('basket_id', $activeBasket->id)
            ->first();

        if (!$basketItem) {
            return redirect()
                ->route('basket.index')
                ->with('error', 'Позиция в корзине не найдена!');
        }

        $product = $basketItem->product;

        if (!$product->hasStock($newQuantity)) {
            return redirect()
                ->route('basket.index')
                ->with('error', "Недостаточно товара '{$product->name}' на складе! Доступно: {$product->stock_quantity}");
        }

        $basketItem->quantity = $newQuantity;
        $basketItem->save();

        $activeBasket->load(['items.product']);

        broadcast(new BasketUpdateEvent(
            userId: $user->id,
            totalItems: $activeBasket->totalItems(),
            totalSum: $activeBasket->totalSum()
        ));

        return redirect()
            ->route('basket.index')
            ->with('success', 'Количество товара успешно обновлено!');
    }

    public function remove($itemId): RedirectResponse
    {
        $user = Auth::user();

        $activeBasket = $user->activeBasket();

        if (!$activeBasket) {
            return redirect()
                ->route('basket.index')
                ->with('error', 'Корзина не найдена!');
        }

        $basketItem = BasketItem::where('id', $itemId)
            ->where('basket_id', $activeBasket->id)
            ->first();

        if (!$basketItem) {
            return redirect()
                ->route('basket.index')
                ->with('error', 'Позиция в корзине не найдена!');
        }

        $basketItem->delete();

        $activeBasket->load(['items.product']);

        broadcast(new BasketUpdateEvent(
            userId: $user->id,
            totalItems: $activeBasket->totalItems(),
            totalSum: $activeBasket->totalSum()
        ));

        return redirect()
            ->route('basket.index')
            ->with('success', 'Товар успешно удален из корзины!');
    }

    public function clear(): RedirectResponse
    {
        $user = Auth::user();

        $activeBasket = $user->activeBasket();

        if (!$activeBasket) {
            return redirect()
                ->route('basket.index')
                ->with('error', 'Корзина не найдена!');
        }

        BasketItem::where('basket_id', $activeBasket->id)->delete();

        broadcast(new BasketUpdateEvent(
            userId: $user->id,
            totalItems: 0,
            totalSum: 0
        ));

        return redirect()
            ->route('basket.index')
            ->with('success', 'Корзина успешно очищена!');
    }

    public function checkout(CheckoutRequest $request): RedirectResponse
    {
        $user = Auth::user();

        $activeBasket = $user->activeBasket();

        if (!$activeBasket) {
            return redirect()
                ->route('basket.index')
                ->with('error', 'Корзина не найдена!');
        }

        $activeBasket->load(['items.product']);

        if ($activeBasket->items->isEmpty()) {
            return redirect()
                ->route('basket.index')
                ->with('error', 'Корзина пуста! Добавьте товары перед оформлением.');
        }

        foreach ($activeBasket->items as $item) {
            $product = $item->product;

            if (!$product->hasStock($item->quantity)) {
                return redirect()
                    ->route('basket.index')
                    ->with('error', "Недостаточно товара '{$product->name}' на складе! Доступно: {$product->stock_quantity}");
            }
        }

        foreach ($activeBasket->items as $item) {
            $product = $item->product;
            $product->decrementStock($item->quantity);
        }

        $activeBasket->status = 'completed';
        $activeBasket->save();

        Basket::create([
            'user_id' => $user->id,
            'status' => 'pending',
        ]);

        broadcast(new BasketCompletedEvent(
            userId: $user->id,
            basketId: $activeBasket->id,
            totalSum: $activeBasket->totalSum()
        ));

        return redirect()
            ->route('my-orders.index')
            ->with('success', 'Заказ успешно оформлен!');
    }
}
