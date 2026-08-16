<h1>Order Confirmation</h1>

<p>Hi {{ $order->user->name }},</p>

<p>Your order #{{ $order->id }} has been confirmed!</p>

<p><strong>Total: ${{ $order->total_amount }}</strong></p>

<p>Items:</p>
<ul>
    @foreach($order->orderItems as $item)
        <li>{{ $item->quantity }}x Product - ${{ $item->price }}</li>
    @endforeach
</ul>

<p>Thank you!</p>
