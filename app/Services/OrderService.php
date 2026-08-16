<?php

namespace App\Services;

use App\Jobs\CreateInvoice;
use App\Jobs\MarkOrderComplete;
use App\Jobs\SendConfirmationEmail;
use App\Jobs\SendSMS;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Exceptions\InsufficientStockException;
use Exception;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function createOrder(User $user, array $data): Order
    {
       $order= DB::transaction(function () use ($user, $data) {
            $total = 0;
            $preparedItems = [];

            foreach ($data['items'] as $item) {
                $product = Product::lockForUpdate()->findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new InsufficientStockException(
                        $product->name,
                        $item['quantity'],
                        $product->stock
                    );
                }

                $subtotal = $product->price * $item['quantity'];
                $total += $subtotal;

                $preparedItems[] = [
                    'product' => $product,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ];
            }

            $order = Order::create([
                'user_id' => $user->id,
                'total_amount' => $total,
            ]);

            foreach ($preparedItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'vendor_id' => $item['product']->vendor_id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'item_status' => 'pending',
                ]);
                $item['product']->decrement(
                    'stock',
                    $item['quantity']
                );
            }
           return $order;
        });
        Bus::chain([
            new CreateInvoice($order),
            new SendConfirmationEmail($order),
            new SendSMS($order),
            new MarkOrderComplete($order),
        ])->onConnection('database')
            ->onQueue('default')
            ->dispatch();
        return $order->load('orderItems', 'user');
        }
    public function getAllUserOrders(User $user)
    {
        return Order::where('user_id' , $user->id )->with('orderItems')->paginate(50);
    }

    public function getOrder(Order $order)
    {
        return $order->load('orderItems');
    }
    public function deleteOrder(Order $order)
    {
        if($order->status !== 'pending') {
            throw new Exception("Cannot delete order with status: {$order->status}");
        }
        return $order->delete();
    }
}
