<div>
    <!-- Order Summary Section -->
    <div style="display: flex; flex-wrap: wrap; margin-bottom: 20px;">
        <div style="flex: 1 1 30%; height: 30px;">
            <p><b>Order Number:</b> {{ $order->order_number }}</p>
        </div>
        <div style="flex: 1 1 30%;">
            <p><b>Customer Name:</b> {{ $order->customer_name }}</p>
        </div>
        <div style="flex: 1 1 30%;">
            <p><b>Customer Mobile:</b> {{ $order->customer_mobile }}</p>
        </div>
        <div style="flex: 1 1 30%;">
            <p><b>Total Products:</b> {{ $order->total_products }}</p>
        </div>
        <div style="flex: 1 1 30%;">
            <p style="margin: 0;"><b>Total Amount:</b> {{ $order->total_amount }}</p>
        </div>
        <div style="flex: 1 1 30%;">

        </div>
    </div>

    <!-- Product List Header -->
    <h5>List of Products</h5>

    <!-- Product List Table -->
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th style="border: 1px solid black; text-align: center;">Sr. No</th>
                <th style="border: 1px solid black; text-align: center;">Product Name</th>
                <th style="border: 1px solid black; text-align: center;">Quantity</th>
                <th style="border: 1px solid black; text-align: center;">Size(Height x Width)</th>
                <th style="border: 1px solid black; text-align: center;">Total Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order_details as $key => $item)
                <tr>
                    <td style="border: 1px solid black; text-align: center;">{{ $key + 1 }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $item->name }}</td>
                    <td style="border: 1px solid black; text-align: center; justify-content:center;">{{ $item->quantity }}</td>
                    <td style="border: 1px solid black; text-align: center;">{{ $item->height . ' x ' . $item->width }}</td>
                    <td style="border: 1px solid black; text-align: center; justify-content:center;">{{ $item->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
