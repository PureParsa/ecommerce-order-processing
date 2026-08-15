<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;


class OrderController extends Controller
{
    public function __construct(private OrderService $orderService)
    {
    }

    public function store(StoreOrderRequest $request)
    {
        $order = $this->orderService->createOrder(
            auth()->user(),
            $request->validated()
        );

        return new OrderResource($order);
    }

    public function index()
    {
        $user = auth()->user();
        $orders = $this->orderService->getAllUserOrders($user);
        return OrderResource::collection($orders);
    }
    public function show(Order $order)
    {
        $orders = $this->orderService->getOrder($order);
        return new OrderResource($orders);
    }
    public function destroy(Order $order)
    {
        $this->orderService->deleteOrder($order);
        return response()->noContent();
    }

}
