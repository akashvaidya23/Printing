
        @foreach ($orders as $key=>$order)
            <tr>
                <td class="cell">{{$key + $orders->firstItem()}}</td>
                <td class="cell">{{ \Carbon\Carbon::parse($order->order_date)->format('d - F - Y') }}</td>
                <td class="cell">{{$order->total_orders}}</td>
                <td class="cell">{{$order->total_products}}</td>
                <td class="cell">{{ 'Rs. ' . $order->total_paid . '/-'}}</td>
                <td class="cell">{{ 'Rs. ' . $order->total_due . '/-'}}</td>
                <td class="cell">{{ 'Rs. ' . $order->total_amount . '/-' }} </td>
            </tr>
        @endforeach