<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .main {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
            padding: 20px;
            margin-right: auto;
            margin-left: auto;
            max-width: 600px;
            width: 90%;
            border-radius: 8px;
        }

        .bordered-content {
            width: 100%;
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
        }

        .table-container {
            max-width: 60%;
            margin: 0 auto;
        }

        .cell {
            border: 1px solid black;
            text-align: center;
        }

        .button-container {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin: 20px;
        }

        .search-input {
            width: 200px;
            border: 1px solid black;
        }
        
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="{{route('dashboard')}}">Mahalaxmi Flex</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{route('product.create')}}">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{route('billing.create')}}">Billing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{route('order.index')}}">Orders</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="main">
        <h4>Orders Summary</h4>
    </div>

    <br>

    <div class="table-container" >
        <div class="button-container">
            <form id="searchOrders" class="d-flex">
                @csrf
                <input type="date" class="form-control search-input" name="query" placeholder="From Date" id="searchOrderFrom">
                <input type="date" class="form-control search-input" name="query" placeholder="From Date" id="searchOrderTo">
            </form>
            <a href="/dashboard" class="btn btn-dark">Clear</a>
        </div>
        <table class="table table-striped" id="dashboard">
            <thead>
                <tr>
                    <th class="cell" scope="col">Sr. No</th>
                    <th class="cell" scope="col">Date</th>
                    <th class="cell" scope="col">Total Orders</th>
                    <th class="cell" scope="col">Total Products</th>
                    <th class="cell" scope="col">Total Paid</th>
                    <th class="cell" scope="col">Total Due</th>
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
                        <td class="cell">{{ 'Rs. ' . $order->total_paid . '/-'}}</td>
                        <td class="cell">{{ 'Rs. ' . $order->total_due . '/-'}}</td>
                        <td class="cell">{{ 'Rs. ' . $order->total_amount . '/-' }} </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $orders->links() }}
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
    $("#searchOrderTo").on('change',function(e){
        e.preventDefault();
        let start_date = $('#searchOrderFrom').val();
        let end_date = $('#searchOrderTo').val();
        
        console.log({start_date, end_date});

        if(start_date == null){
            alert('Pleasse select start date');
            return;
        }
        if(start_date != null && end_date != null){
            $.ajax({
                url: '/dashboard/get/'+ start_date + '/' + end_date,
                type: 'GET',
                datatype: 'html',
                success: function (response) {
                    console.log(response);
                    $("#dashboard tbody").html(response);
                }
            });
        }
    })
</script>
</html>