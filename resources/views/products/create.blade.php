<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>List Product</title>
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
            max-width: 1000px;
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
        <h4>Products List</h4>
    </div>

    <br>
    
    <div class="table-container">
        <div class="button-container">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productModal" id="addProductButton">Add New Product</button>
            <br>
            <form id="searchForm" class="d-flex">
                @csrf
                <input type="text" class="form-control search-input" name="query" placeholder="Search Products" id="searchInput">
            </form>
        </div>

        <table class="table table-striped products_table">
            <thead>
                <tr>
                    <th class="cell" scope="col">Sr. No</th>
                    <th class="cell" scope="col">Product Name</th>
                    <th class="cell" scope="col">Price</th>
                    <th class="cell" scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @include('products.partials.product_table_body', ['products' => $products])
            </tbody>
        </table>
        {{ $products->links() }}
    </div>

    <!-- Add/Edit Product Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productModalLabel">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="productForm" action="{{route('product.store')}}" method="POST">
                        @csrf
                        <div class="input-group mb-3">
                            <span class="input-group-text">Name of the Product</span>
                            <input type="text" class="form-control" name="product" id="modalProductName" autocomplete="off">
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text">Price</span>
                            <input type="number" class="form-control" name="price" id="modalProductPrice" autocomplete="off">
                        </div>
                        <input type="hidden" name="_method" id="modalMethod" value="POST">
                        <input type="hidden" name="id" id="modalProductId">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="saveProductButton">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function () {
            const productForm = $('#productForm');
            const saveProductButton = $('#saveProductButton');

            saveProductButton.on('click', function () {
                productForm.submit();
            });

            
            $('.edit-button').on('click', function () {
                const productId = $(this).data('id');
                const productName = $(this).data('name');
                const productPrice = $(this).data('price');

                
                $('#productModalLabel').text('Edit Product');
                $('#modalMethod').val('PUT');
                $('#modalProductId').val(productId);
                $('#modalProductName').val(productName);
                $('#modalProductPrice').val(productPrice);

                productForm.attr('action', `{{ url('product') }}/${productId}`);
            });

            $('#productModal').on('hidden.bs.modal', function () {
                productForm[0].reset();
                $('#modalMethod').val('POST');
                $('#productModalLabel').text('Add New Product');
                productForm.attr('action', "{{ route('product.store') }}");
            });
            
            $('#searchInput').on('input', function (e) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var query = $('#searchInput').val();
                console.log("query ", query);
                $.ajax({
                    url: '{{ route("product_search") }}',
                    type: 'POST',
                    data: { query: query },
                    success: function (data) {
                        $('.products_table tbody').html(data);
                    },
                    error: function (xhr, status, error) {
                        console.error("Search Error: " + error);
                    }
                });
            });
        });
    </script>
</body>

</html>