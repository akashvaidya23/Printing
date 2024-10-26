<table class="table table-striped">
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