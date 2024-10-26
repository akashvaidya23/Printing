<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
            max-width: 60%;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand text-white" href="#">Shreyas</a>
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
                        <a class="nav-link text-white" href="#">Orders</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="main">
        <h4 style="text-align: center">Make Bill Here</h4>
        <br>
        <div class="container text-center">
            <div class="row align-items-end">
                <div class="col">
                    <input type="text" class="form-control" name="cust_name" placeholder="Customer name" id="cust_name">
                </div>
                <div class="col">
                    <input type="text" class="form-control" name="cust_mobile" placeholder="Customer mobile" id="cust_mobile">
                </div>
                <div class="col">
                    <select id="dynamic-select" style="width: 100%" placeholder="Search for options"></select>
                </div>
            </div>
        </div>
        <br><br>
        <div class="table-container">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th class="cell">Sr.No</th>
                        <th class="cell">Name of the Product</th>
                        <th class="cell">Quantity</th>
                        <th class="cell">Price</th>
                        <th class="cell">Total</th>
                        <th class="cell">Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <script>
        $(document).ready(function () {
            $('#dynamic-select').select2({
                placeholder: "Search for options",
                ajax: {
                    url: '{{ route("search_options") }}',
                    type: 'POST',
                    dataType: 'json',
                    delay: 250,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: function (params) {
                        return {
                            query: params.term // Pass the search term
                        };
                    },
                    processResults: function (data) {
                        console.log("data ", data);
                        return {
                            results: data // Return the results in Select2 format
                        };
                    }
                }
            });

            $('#dynamic-select').on('select2:open', function () {
                requestAnimationFrame(() => {
                    const searchField = document.querySelector('.select2-container--open .select2-search__field');
                    if (searchField) {
                        searchField.focus();
                    }
                });
            });
        });
    </script>
</body>

</html>
