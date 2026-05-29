<?php

namespace App\Http\Controllers;

use App\Events\BasketCompletedEvent;
use App\Events\BasketUpdateEvent;
use App\Events\OrderCreatedEvent;
use App\Http\Requests\Basket\AddToBasketRequest;
use App\Http\Requests\Basket\UpdateBasketItemRequest;
use App\Http\Requests\Basket\CheckoutRequest;
use App\Models\Basket;
use App\Models\BasketItem;
use App\Models\Product;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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

    public function state(): JsonResponse
    {
        $user = Auth::user();
        $activeBasket = $user->activeBasket();

        if ($activeBasket) {
            $activeBasket->load(['items.product']);

            $items = [];
            foreach ($activeBasket->items as $item) {
                $price = $item->product->discount_price ?? $item->product->price;
                $items[] = [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'subtotal' => $price * $item->quantity,
                    'product' => [
                        'id' => $item->product->id,
                        'name' => $item->product->name,
                        'price' => $price,
                        'image_url' => $item->product->image_url,
                    ],
                ];
            }

            return response()->json([
                'success' => true,
                'total_items' => $activeBasket->totalItems(),
                'total_sum' => $activeBasket->totalSum(),
                'items' => $items,
            ]);
        }

        return response()->json([
            'success' => true,
            'total_items' => 0,
            'total_sum' => 0,
            'items' => [],
        ]);
    }

    public function add(AddToBasketRequest $request): JsonResponse
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
                return response()->json([
                    'success' => false,
                    'message' => 'Товар не найден!',
                ]);
            }

            if (!$product->hasStock($itemData['quantity'])) {
                return response()->json([
                    'success' => false,
                    'message' => "Недостаточно товара '{$product->name}' на складе! Доступно: {$product->stock_quantity}",
                ]);
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

        return response()->json([
            'success' => true,
            'message' => 'Товары успешно добавлены в корзину!',
        ]);
    }

    public function update(UpdateBasketItemRequest $request, $itemId): JsonResponse
    {
        $user = Auth::user();
        $newQuantity = $request->input('quantity');

        $activeBasket = $user->activeBasket();

        if (!$activeBasket) {
            return response()->json([
                'success' => false,
                'message' => 'Корзина не найдена!',
            ]);
        }

        $basketItem = BasketItem::where('id', $itemId)
            ->where('basket_id', $activeBasket->id)
            ->first();

        if (!$basketItem) {
            return response()->json([
                'success' => false,
                'message' => 'Позиция в корзине не найдена!',
            ]);
        }

        $product = $basketItem->product;

        if (!$product->hasStock($newQuantity)) {
            return response()->json([
                'success' => false,
                'message' => "Недостаточно товара '{$product->name}' на складе! Доступно: {$product->stock_quantity}",
            ]);
        }

        $basketItem->quantity = $newQuantity;
        $basketItem->save();

        $activeBasket->load(['items.product']);

        broadcast(new BasketUpdateEvent(
            userId: $user->id,
            totalItems: $activeBasket->totalItems(),
            totalSum: $activeBasket->totalSum()
        ));

        return response()->json([
            'success' => true,
            'message' => 'Количество товара успешно обновлено!',
        ]);
    }

    public function remove($itemId): JsonResponse
    {
        $user = Auth::user();

        $activeBasket = $user->activeBasket();

        if (!$activeBasket) {
            return response()->json([
                'success' => false,
                'message' => 'Корзина не найдена!',
            ]);
        }

        $basketItem = BasketItem::where('id', $itemId)
            ->where('basket_id', $activeBasket->id)
            ->first();

        if (!$basketItem) {
            return response()->json([
                'success' => false,
                'message' => 'Позиция в корзине не найдена!',
            ]);
        }

        $basketItem->delete();

        $activeBasket->load(['items.product']);

        broadcast(new BasketUpdateEvent(
            userId: $user->id,
            totalItems: $activeBasket->totalItems(),
            totalSum: $activeBasket->totalSum()
        ));

        return response()->json([
            'success' => true,
            'message' => 'Товар успешно удален из корзины!',
        ]);
    }

    public function clear(): JsonResponse
    {
        $user = Auth::user();

        $activeBasket = $user->activeBasket();

        if (!$activeBasket) {
            return response()->json([
                'success' => false,
                'message' => 'Корзина не найдена!',
            ]);
        }

        BasketItem::where('basket_id', $activeBasket->id)->delete();

        broadcast(new BasketUpdateEvent(
            userId: $user->id,
            totalItems: 0,
            totalSum: 0
        ));

        return response()->json([
            'success' => true,
            'message' => 'Корзина успешно очищена!',
        ]);
    }

    public function checkout(CheckoutRequest $request): JsonResponse
    {
        $user = Auth::user();

        $activeBasket = $user->activeBasket();

        if (!$activeBasket) {
            return response()->json([
                'success' => false,
                'message' => 'Корзина не найдена!',
            ]);
        }

        $activeBasket->load(['items.product']);

        if ($activeBasket->items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Корзина пуста! Добавьте товары перед оформлением.',
            ]);
        }

        foreach ($activeBasket->items as $item) {
            $product = $item->product;

            if (!$product->hasStock($item->quantity)) {
                return response()->json([
                    'success' => false,
                    'message' => "Недостаточно товара '{$product->name}' на складе! Доступно: {$product->stock_quantity}",
                ]);
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

        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $notification = Notification::create([
                'user_id' => $admin->id,
                'basket_id' => $activeBasket->id,
                'title' => 'Новый заказ!',
                'message' => "Пользователь {$user->name} оформил заказ #{$activeBasket->id} на сумму {$activeBasket->totalSum()} ₽",
                'link' => route('admin.orders.show', $activeBasket->id),
                'is_read' => false,
            ]);

            broadcast(new OrderCreatedEvent($notification));
        }

        return response()->json([
            'success' => true,
            'message' => 'Заказ успешно оформлен!',
            'redirect' => route('my-orders.index'),
        ]);
    }
}
