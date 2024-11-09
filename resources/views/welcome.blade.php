<!-- resources/views/yourview.blade.php -->
@extends('layouts.app')

@section('title', 'Your Page Title')

@section('content')
<div class="main">
    <h4>Orders Summary</h4>
</div>

<br>

<div class="table-container">
    <table class="table table-striped">
        <thead>
            <tr>
                <th class="cell" scope="col">Sr. No</th>
                <th class="cell" scope="col">Date</th>
                <th class="cell" scope="col">Total Orders</th>
                <th class="cell" scope="col">Total Products</th>
                <th class="cell" scope="col">Total Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $key=>$order)
                <tr>
                    <td class="cell">{{$key + $orders->firstItem()}}</td>
                    <td class="cell">{{ \Carbon\Carbon::parse($order->order_date)->format('d - F - Y') }}</td>
                    <td class="cell">{{$order->total_orders}}</td>
                    <td class="cell">{{$order->total_products}}</td>
                    <td class="cell">{{ 'Rs. ' . $order->total_amount . ' /-' }} </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $orders->links() }}
</div>
    <!-- Page-specific content goes here -->
@endsection
