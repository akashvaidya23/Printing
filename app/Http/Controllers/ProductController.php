<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::orderBy('id', 'desc')
            ->paginate(100);
        return view('products.create', compact("products"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $product = new Product;
            $product->name = $request->product;
            $product->price = $request->price;
            $product->save();
        } catch (Exception $e){
            Log::emergency("Something went wrong",["message" => $e->getMessage(), "file" => $e->getFile(), "line" => $e->getLine()]);
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        try{
            $product = Product::find($product->id);
            $product->name = $request->product;
            $product->price = $request->price;
            $product->save();
        } catch (Exception $e){
            Log::emergency("Something went wrong",["message" => $e->getMessage(), "file" => $e->getFile(), "line" => $e->getLine()]);
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product = Product::find($product->id)
            ->delete();

        return redirect()->back();
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $products = Product::where('name', 'LIKE', '%' . $query . '%')
            ->paginate(100);

        return view("products.partials.product_table_body", compact('products'));
    }

    public function search_options(Request $request)
    {
        $query = $request->input('query');
        if(empty($query)) {
            return response()->json([]);
        }
        
        $results = Product::where('name', 'LIKE', '%' . $query . '%')
            ->select('id', 'name')
            ->limit(10)
            ->get();
    
        $formattedResults = $results->map(function ($item) {
            return ['id' => $item->id, 'text' => $item->name];
        });
    
        return response()->json($formattedResults);
    }
}
