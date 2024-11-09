<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            font-size: 12px; /* Reduced font size for more content */
        }

        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        /* Header Section */
        .header {
            width: 100%;
            margin-bottom: 20px;
        }

        .header table {
            width: 100%;
            border-collapse: collapse;
        }

        .header .company-info {
            font-size: 14px;
            color: #333;
        }

        .header .company-info p {
            margin: 2px 0;
        }

        .header .company-logo {
            text-align: right;
            max-width: 120px;
        }

        .header .company-logo img {
            max-width: 100%;
            height: auto;
        }

        /* Invoice Title */
        .invoice-title {
            text-align: center;
            margin-bottom: 20px;
        }

        .invoice-title h1 {
            font-size: 28px; /* Reduced title font size */
            margin: 0;
            font-weight: bold;
            color: #333;
        }

        .invoice-title p {
            font-size: 12px;
            color: #777;
        }

        /* Invoice Details Section */
        .invoice-details {
            width: 100%;
            margin-bottom: 20px;
        }

        .invoice-details table {
            width: 100%;
            border-collapse: collapse;
        }

        .invoice-details table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-details h3 {
            margin-bottom: 8px;
            font-size: 14px;
            color: #333;
            font-weight: bold;
        }

        .invoice-details p {
            font-size: 12px;
            margin: 5px 0;
            color: #555;
        }

        /* Product Table */
        table.products {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.products th,
        table.products td {
            padding: 8px 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table.products th {
            background-color: #f2f2f2;
            font-weight: 600;
            color: #333;
        }

        table.products td {
            color: #555;
        }

        /* Total Calculation */
        table.totals {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.totals tr.total-row td {
            font-weight: 600;
            color: #333;
            padding-right: 10px;
        }

        table.totals tr.total-row td:first-child {
            text-align: right;
            padding-left: 10px;
            font-size: 14px;
        }

        table.totals tr.total-row td:last-child {
            text-align: right;
            font-size: 16px;
            font-weight: bold;
            color: #000;
        }

        /* Footer Section */
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .footer p {
            margin: 5px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header Section with Logo and Company Info -->
        <div class="header">
            <table>
                <tr>
                    <td class="company-info">
                        <p><strong>Company Name</strong></p>
                        <p>Address Line 1, Address Line 2</p>
                        <p>Email: info@company.com | Phone: (123) 456-7890</p>
                    </td>
                    <td class="company-logo">
                        <img src="{{ asset('images/your_logo.png') }}" alt="Company Logo" />
                    </td>
                </tr>
            </table>
        </div>

        <!-- Invoice Title -->
        <div class="invoice-title">
            <h1>Invoice</h1>
            <p>Invoice Number: <strong>{{ $order->order_number }}</strong></p>
            <p>Date: {{ \Carbon\Carbon::parse($order->created_at)->format('d-F-Y') }}</p>
        </div>

        <!-- Invoice Details -->
        <div class="invoice-details">
            <table>
                <tr>
                    <!-- Billing To -->
                    <td class="billing-to" style="width: 48%;">
                        <h3>Billing To:</h3>
                        <p><strong>{{ $order->customer_name }}</strong></p>
                        <p>Mobile: {{ $order->customer_mobile }}</p>
                    </td>
                    <!-- Invoice To -->
                    <td class="invoice-to" style="width: 48%;">
                        <h3>Invoice To:</h3>
                        <p><strong>Company Name</strong></p>
                        <p>Company Address Line 1</p>
                        <p>Company Address Line 2</p>
                        <p>Email: contact@company.com</p>
                        <p>Phone: (098) 765-4321</p>
                    </td>
                </tr>
            </table>
        </div>

        <!-- Product Table -->
        <table class="products">
            <thead>
                <tr>
                    <th>Sr. No</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Height</th>
                    <th>Width</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $key => $product)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->quantity }}</td>
                        <td>{{ $product->height }}</td>
                        <td>{{ $product->width }}</td>
                        <td>{{ number_format($product->price, 2) }}</td>
                        <td>{{ number_format($product->total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Total Calculation -->
        <table class="totals">
            <tr class="total-row">
                <td>Total Quantity:</td>
                <td>{{ number_format($order->total_products, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td>Total Amount:</td>
                <td>{{ number_format($order->total_amount, 2) }}</td>
            </tr>
        </table>

        <!-- Footer Section -->
        <div class="footer">
            <p>Thank you for your business!</p>
            <p>If you have any questions, please contact us at info@company.com.</p>
        </div>
    </div>
</body>

</html>