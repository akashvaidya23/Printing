<tr>
    <td class="cell">{{$row_count}}</td>
    <td class="cell">
        <input type="hidden" name="products[{{$row_count}}][product_id]" class="product_id" value="{{$product_details->id}}">
        {{$product_details->name}}
        <input type="hidden" name="products[{{$row_count}}][product_name]" class="product_name" value="{{$product_details->name}}">
    </td>
    <td class="cell">
        <input type="number" class="form-control quantity" value="1" name="products[{{$row_count}}][quantity]">
    </td>
    <td class="cell">
        <input type="text" class="form-control height" name="products[{{$row_count}}][height]">
    </td>
    <td class="cell">
        <input type="text" class="form-control width" name="products[{{$row_count}}][width]">
    </td>
    <td class="cell">
        <input type="number" class="form-control price" name="products[{{$row_count}}][price]" value="{{number_format($product_details->price, 2)}}">
    </td>
    <td class="cell">
        <input type="number" class="form-control total" name="products[{{$row_count}}][total]" value="0.00" readonly>
    </td>
    <td class="cell">
        {{-- <button class="btn btn-danger delete-button" data-id="{{$product_details->id}}">Delete</button>
        <button type="button" class="btn btn-success add-button">Add</button> --}}
        <i class="bi bi-trash delete-button" style="cursor: pointer;" data-id="{{$product_details->id}}"></i>
        <i class="bi bi-plus add-button ms-2" style="cursor: pointer;"></i>
    </td>
</tr>
