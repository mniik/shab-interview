<!DOCTYPE html>

<html

    lang="en">

<head>


    <meta

        charset="UTF-8">


    <meta

        name="viewport"

        content="width=device-width, initial-scale=1.0">


    <title>Order Placed Notification</title>
</head>
<body>
<h1>Hi,</h1>
<p>Customer order ({{ $order->id }}) has been placed!</p>
<p>Here are some details:</p>
<ul>
    <li>Items:</li>
    @foreach ($order->items as $item)
        <li>{{ $item->product->title . '-' . $item->quantity}}</li>
    @endforeach
</ul>
</body>
</html>
