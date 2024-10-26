@foreach ($products as $key => $product)
    <tr>
        <td class="cell">{{$key + $products->firstItem()}}</td>
        <td class="cell">{{$product->name}}</td>
        <td class="cell">{{$product->price}}</td>
        <td class="cell">
            <button class="btn btn-info edit-button" data-id="{{$product->id}}" data-name="{{$product->name}}" data-price="{{$product->price}}" data-bs-toggle="modal" data-bs-target="#productModal">Edit</button>
            <form action="{{ route('product.destroy', $product->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </td>
    </tr>    
@endforeach
