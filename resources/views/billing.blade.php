<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Billing Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        .main{
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .cell{
            border: 1px solid black;
            text-align: center;
        }

        .table-container {
            max-width: 95%;
            margin: 0 auto;
        }
        
        .custom-selection {
            height: 40px;
            line-height: 40px;
        }
        
        .custom-dropdown .select2-results__option {
            padding: 15px 10px;
        }

        .select2-container .select2-selection--single {
            height: 40px;
        }

        .select2-container .select2-selection--single .select2-selection__rendered {
            line-height: 40px;
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
                        <a class="nav-link text-white" href="{{ route('product.create') }}">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('billing.create') }}">Billing</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{route('order.index')}}">Orders</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="main">
        <h4 style="text-align: center">Make Bill Here</h4>
        <br>
        <form action="{{route('billing.store')}}" id="submit_bill" method="POST">
            @csrf
            <div class="container text-center">
                <div class="row align-items-end">
                    <div class="col">
                        <input type="text" class="form-control" name="cust_name" placeholder="Customer name" id="cust_name" autocomplete="off">
                    </div>
                    <div class="col">
                        <input type="number" maxlength="10" minlength="10" class="form-control" name="cust_mobile" placeholder="Customer mobile" id="cust_mobile" autocomplete="off">
                    </div>
                    <div class="col">
                        <input class="form-control" id="dynamic-input" style="width: 100%;" placeholder="Search Product">
                    </div>
                </div>
            </div>
            <br><br>
            <div class="table-container">
                <table class="table table-striped" id="order_products">
                    <thead>
                        <tr>
                            <th class="cell">Sr.No</th>
                            <th class="cell">Name of the Product</th>
                            <th class="cell">Quantity</th>
                            <th class="cell">Height</th>
                            <th class="cell">Width</th>
                            <th class="cell">Price</th>
                            <th class="cell">Total</th>
                            <th class="cell">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <th class="cell" colspan="2"></th>
                        <th class="cell totalQty">
                            Total Quantity:                            
                        </th>
                        <th class="cell totalPrice" colspan="4">
                            Total Price:
                        </th>
                        <th class="cell"></th>
                    </tfoot>
                </table>
            </div>
            <div class="container text-center">
                <div class="row align-items-end">
                    <div class="col">
                    </div>
                    <div class="col">
                        <input type="number" class="form-control" name="total_paid" placeholder="Amount Paid" id="total_paid" autocomplete="off">
                    </div>
                    <div class="col">
                        <input type="number" class="form-control" name="total_due" placeholder="Amount Due" id="total_due" autocomplete="off">
                    </div>
                </div>
            </div>
            <input type="hidden" name="total_qty" class="total_qty" id="total_qty">
            <input type="hidden" name="total_price" class="total_price" id="total_price">
            <br>

            <div style="text-align: center">
                <button type="submit" class="btn btn-dark">Submit</button>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <script>
        $(document).ready(function () {
            toggleSubmitButton();

            function toggleSubmitButton() {
                if ($('#order_products tbody tr').length === 0) {
                    $('#submit_bill button[type="submit"]').prop('disabled', true);
                } else {
                    $('#submit_bill button[type="submit"]').prop('disabled', false);
                }
            }

            $("#dynamic-input").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: '{{ route("search_options") }}',
                        type: 'POST',
                        dataType: 'json',
                        delay: 250,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            query: request.term
                        },
                        success: function(data) {
                            response($.map(data, function(item) {
                                return {
                                    label: item.text,
                                    value: item.id
                                };
                            }));
                        }
                    });
                },
                minLength: 2,
                select: function(event, ui) {
                    let productExists = false;

                    $('#order_products tbody tr').each(function() {
                        const rowProductId = $(this).find('.product_id').val();
                        if (rowProductId == ui.item.value) {
                            const quantityInput = $(this).find('.quantity');
                            const currentQuantity = parseInt(quantityInput.val()) || 0;
                            quantityInput.val(currentQuantity + 1);
                            productExists = true;
                            updateRowTotal($(this));
                        }
                    });

                    if (!productExists) {
                        let rowCount = $('#order_products tbody tr').length + 1;
                        $.ajax({
                            url: '{{ route("add_product") }}',
                            type: 'POST',
                            dataType: 'html',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            data: {
                                product_id: ui.item.value,
                                rowCount: rowCount
                            },
                            success: function(response) {
                                $('#order_products tbody').append(response);
                                updateTableTotals();
                                toggleSubmitButton();
                            },
                            error: function(xhr, status, error) {
                                console.error("Error fetching row data:", error);
                            }
                        });
                    }

                    // Clear dynamic-input and trigger change to fully reset the field
                    setTimeout(() => {
                        $('#dynamic-input').val("").trigger('change');
                    }, 100);  // Adjust the timeout if necessary

                    updateTableTotals();
                }

            });

            // Event listener for quantity change
            $('#order_products').on('input', '.quantity, .height, .width, .price', function () {
                updateRowTotal($(this).closest('tr'));
                updateTableTotals();
            });

            // Function to calculate and update the total for each row
            function updateRowTotal(row) {
                var quantity = parseFloat(row.find('.quantity').val()) || 0;
                var height = parseFloat(row.find('.height').val()) || 0;
                var width = parseFloat(row.find('.width').val()) || 0;
                var price = parseFloat(row.find('.price').val()) || 0;
                var total = quantity * height * width * price;
                console.log('total ', total);
                
                row.find('.total').val(total.toFixed(2));
            }

            // Function to calculate and update the table totals (Total Quantity and Total Price)
            function updateTableTotals() {
                let totalSum = 0;
                let totalQty = 0;
                
                $('#order_products tbody tr').each(function() {
                    let quantity = parseFloat($(this).find('.quantity').val()) || 0;
                    let height = parseFloat($(this).find('.height').val()) || 0;
                    let width = parseFloat($(this).find('.width').val()) || 0;
                    let price = parseFloat($(this).find('.price').val()) || 0;
                    totalQty += quantity;
                    totalSum += quantity * height * width * price;
                });
                
                // Update the footer with the total quantity and total price
                $('#order_products tfoot .totalQty').text(`Total Quantity: ${totalQty.toFixed(2)}`);
                $('#order_products tfoot .totalPrice').text(`Total Price: ${totalSum.toFixed(2)}`);
                $('#total_price').val(totalSum.toFixed(2));
                $('#total_qty').val(totalQty);
            }

            // Event listener to remove a row and recalculate totals
            $('#order_products').on('click', '.delete-button', function(e){
                $(this).closest('tr').remove();
                updateRowIds();
                updateRowIndices(); 
                updateTableTotals();
                updateAllRowIndices();
                calculateTotal();
            });

            $('#submit_bill').on('submit', function(event) {
                console.log(event.key);
                if (event.key === 'Enter') {
                    event.preventDefault();
                    return;
                }
                event.preventDefault();
                $.ajax({
                    url: "{{ route('generate.pdf') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function(blob) {
                        const url = window.URL.createObjectURL(blob);
                        const iframe = document.createElement('iframe');
                        iframe.style.display = 'none';
                        iframe.src = url;
                        document.body.appendChild(iframe);
                        iframe.contentWindow.print();
                        window.URL.revokeObjectURL(url); // Clean up URL
                        iframe.onload = function () {
                            iframe.contentWindow.print();
                            $('#order_products tbody').empty(); // Clear table rows
                            $('#total_qty').val('');
                            $('#total_price').val('');
                            $('#order_products tfoot .totalQty').text('Total Quantity:');
                            $('#order_products tfoot .totalPrice').text('Total Price:');
                            window.URL.revokeObjectURL(url); // Clean up URL
                            $('#submit_bill')[0].reset();
                            toggleSubmitButton();
                        };
                    },
                    error: function(xhr, status, error) {
                        console.error("Error generating PDF:", error);
                    }
                });
            });

            $(document).on('keydown', '#dynamic-input', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                }
            });

            $(document).on('focus', '#dynamic-input', function(event) {
                $(this).val("");
            });

            function updateRowIds() {
                $('tbody tr').each(function(index) {
                    $(this).attr('id', 'row-' + (index + 1));  // Adjust the row ID based on the row index
                });
            }
        });

        $('#order_products').on('click', '.add-button', function () {
            const currentRow = $(this).closest('tr');
            const rowIndex = parseInt(currentRow.find('td:first').text()) || 1;
            const newRow = currentRow.clone();
            newRow.find('td:first').text(rowIndex + 1);
            updateRowNames(newRow, rowIndex + 1);
            newRow.insertAfter(currentRow);
            updateAllRowIndices();
        });

        function updateRowIndices() {
            $('#order_products tbody tr').each(function (index) {
                $(this).find('td:first').text(index + 1); // Update the Sr.No column
            });
        }

        function updateAllRowIndices() {
            $('#order_products tbody tr').each(function (i) {
                const newIndex = i + 1;
                $(this).find('td:first').text(newIndex);
                updateRowNames($(this), newIndex);
            });
        }

        function updateRowNames(row, index) {
            row.find('input').each(function () {
                const name = $(this).attr('name');
                const updatedName = name.replace(/\[\d+\]/, `[${index}]`);
                $(this).attr('name', updatedName);
            });
        }

        $("#total_paid").on('input', function(e){
            e.preventDefault();
            // var total_paid = $("#total_paid").val();
            // var total_amount = $("#total_price").val();
            // var total_due = total_amount - total_paid;
            // console.log(total_amount, total_paid, total_paid);
            // if(total_due < 0){
            //     $("#total_due").val(0.00);    
            // } else {
            //     $("#total_due").val(total_due);
            // }
            calculateTotal();
        });

        function calculateTotal() {
            var total_paid = $("#total_paid").val();
            var total_amount = $("#total_price").val();
            var total_due = total_amount - total_paid;
            console.log(total_amount, total_paid, total_paid);
            if(total_due < 0){
                $("#total_due").val(0.00);    
            } else {
                $("#total_due").val(total_due);
            }
        }
    </script>
</body>
</html>
