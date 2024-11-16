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
                        <a class="nav-link text-white" href="{{route('order.index')}}">Orders</a>
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
                    <th class="cell" scope="col">Total Paid</th>
                    <th class="cell" scope="col">Total Due</th>
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
                        <td class="cell">{{$order->total_paid}}</td>
                        <td class="cell">{{$order->total_due}}</td>
                        <td class="cell">{{$order->total_amount}}</td>
                        <td class="cell" style="white-space: nowrap;">
                            <a href="javascript:void(0);" data-id="{{ $order->id }}" class="btn btn-info view-order" style="display: inline-block;">View</a>
                            <a href="javascript:void(0);" data-id="{{ $order->id }}" class="btn btn-primary generate-invoice" style="display: inline-block;">Invoice</a>
                            @if ($order->total_due != 0)
                                <a href="#" data-id="{{ $order->id }}" class="btn btn-primary add-payment" style="display: inline-block;">Add Payment</a>
                            @endif
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

    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">Payments for Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div>
                        <p id="total_paid_model">Total Paid</p>
                        <p id="total_due_model">Total Due</p>
                    </div>
                    <div id="existingPayments">
                        
                    </div>
                    <hr>
                    <form id="newPaymentForm">
                        <h5>Add New Payment</h5>
                        <div class="mb-3">
                            <label for="paymentAmount" class="form-label">Payment Amount</label>
                            <input type="number" id="paymentAmount" name="paymentAmount" class="form-control" min="1" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Payment</button>
                    </form>
                    
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

        $('.add-payment').on('click', function () {
            const orderId = $(this).data('id');
            const url = "{{ route('getOrderPayments', ':id') }}".replace(':id', orderId);

            // Load existing payments
            $.ajax({
                url: url,
                type: 'GET',
                success: function (response) {
                    $('#existingPayments').html(response.existingPaymentsHtml);
                    $('#total_paid_model').text("Total Paid: " + response.totalPaid);
                    $('#total_due_model').text("Total Due: " + response.totalDue);
                    if (response.totalDue === 0) {
                        $('#newPaymentForm').hide(); // Hide form if due is zero
                    } else {
                        $('#newPaymentForm').show(); // Show form if there is due
                    }
                    $('#paymentModal').modal('show');
                },
                error: function (xhr, status, error) {
                    console.error("Error loading payments:", error);
                    alert("Could not load payment options. Please try again later.");
                }
            });
        });

        $('#newPaymentForm').on('submit', function (event) {
            event.preventDefault();
            
            const orderId = $('.add-payment').data('id'); // Get the order ID
            const paymentAmount = $('#paymentAmount').val();
            const totalDue = parseFloat($('#total_due_model').text().replace("Total Due: ", ""));
            console.log({orderId, paymentAmount, totalDue});
            if (paymentAmount > totalDue) {
                alert("The payment amount cannot exceed the total due amount.");
                return;
            }
            $.ajax({
                url: "{{ route('addOrderPayment') }}",
                type: 'POST',
                dataType: 'html',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    order_id: orderId,
                    amount: paymentAmount
                },
                success: function (response) {
                    // Update the modal with new payment details
                    const data = JSON.parse(response)
                    console.log(data.newTotalDue);
                    
                    $('#existingPayments').html(data.updatedPaymentsHtml);
                    $('#paymentAmount').val('');
                    $('#total_paid_model').text('Total Paid: ' + data.newTotalPaid);
                    $('#total_due_model').text('Total Due: ' + data.newTotalDue);
                    // Update the main table row with new totals
                    // const orderRow = $('a.add-payment[data-id="' + orderId + '"]').closest('tr');
                    // orderRow.find('.cell:eq(5)').text(response.newTotalPaid);
                    // orderRow.find('.cell:eq(6)').text(response.newTotalDue);

                    if (data.newTotalDue == 0) {
                        $('#newPaymentForm').hide();
                    } else {
                        $('#newPaymentForm').show();
                    }
                    $('#paymentModal').modal('show');
                    alert("Payment added successfully.");
                },
                error: function (xhr, status, error) {
                    console.error("Error adding payment:", error);
                    alert("Failed to add payment. Please try again.");
                }
            });
        });
    });
</script>
</html>