<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Orders List</title>
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
    <div id="pdfContainer" style="display: none; margin-top: 20px;">
        <iframe id="pdfViewer" width="100%" height="600px"></iframe>
    </div>
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
                        <a class="nav-link text-white" href="#">Orders</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="main">
        <h4>Orders List</h4>
    </div>

    <br>

    <div class="table-container">
        <form id="searchForm" class="d-flex" style="float:right">
            @csrf
            <input type="text" class="form-control search-input" name="query" placeholder="Search" id="searchInput">
        </form>
        <br><br>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="cell" scope="col">Sr. No</th>
                    <th class="cell" scope="col">Order Number</th>
                    <th class="cell" scope="col">Customer Name</th>
                    <th class="cell" scope="col">Customer Mobile</th>
                    <th class="cell" scope="col">Total Products</th>
                    <th class="cell" scope="col">Total Amount</th>
                    <th class="cell" scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $key=>$order)
                    <tr>
                        <td class="cell">{{$key + $orders->firstItem()}}</td>
                        <td class="cell">{{$order->order_number}}</td>
                        <td class="cell">{{$order->customer_name}}</td>
                        <td class="cell">{{$order->customer_mobile}}</td>
                        <td class="cell">{{$order->total_products}}</td>
                        <td class="cell">{{$order->total_amount}}</td>
                        <td class="cell">
                            <a href="javascript:void(0);" data-id="{{ $order->id }}" class="btn btn-info view-order">View</a>
                            <a href="javascript:void(0);" data-id="{{ $order->id }}" class="btn btn-primary generate-invoice">
                                Invoice
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $orders->links() }}
    </div>

    <!-- Order Details Modal -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDetailsModalLabel">Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="orderDetailsContent"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
    $(document).ready(function () {
        $('.generate-invoice').on('click', function () {
            event.preventDefault();
            let orderId = $(this).data('id');
            let url = "{{ route('generateInvoice', ':id') }}".replace(':id', orderId);
            $.ajax({
                url: url,
                type: "GET",
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(blob) {
                    const url = window.URL.createObjectURL(blob);
                    const iframe = document.createElement('iframe');
                    iframe.style.display = 'none';
                    iframe.src = url;
                    document.body.appendChild(iframe);
                    iframe.onload = function() {
                        iframe.contentWindow.print();
                        window.URL.revokeObjectURL(url); // Clean up URL
                    };
                },
                error: function(xhr, status, error) {
                    console.error("Error generating invoice:", error);
                    alert("Failed to open the invoice. Please try again.");
                }
            });
        });
        
        $('.view-order').on('click', function () {
            var orderId = $(this).data('id');
            var url = "{{ route('billing.show', ':id') }}";
            url = url.replace(':id', orderId);
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'html',
                success: function (data) {
                    $('#orderDetailsContent').html(data);
                    $('#orderDetailsModal').modal('show');
                },
                error: function (xhr, status, error) {
                    console.error("Error fetching order details:", error);
                    alert("Could not fetch order details. Please try again later.");
                }
            });
        });
    });
</script>
</html>