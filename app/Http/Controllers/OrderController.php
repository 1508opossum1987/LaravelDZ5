<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $completedBaskets = $user->completedBaskets()->with(['items.product'])->get();

        return view('orders.index', [
            'completedBaskets' => $completedBaskets,
        ]);
    }
}
