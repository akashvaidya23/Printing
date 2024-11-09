<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Orders_Product;
use App\Models\OrdersProduct;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillingController extends Controller
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
        return view('billing');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $order = new Order;
        $order->order_number = 'abc';
        $order->customer_name = $request->cust_name;
        $order->customer_mobile = $request->cust_mobile;
        $order->total_products = $request->total_qty;
        $order->total_amount = $request->total_price;
        $order->save();

        foreach($request->products as $product){
            DB::table('orders_products')->insert([
                'order_id' => $order->id,
                'product_id' => $product['product_id'],
                'price' => $product['price'],
                'total' => $product['total'],
                'quantity' => $product['quantity'],
                'size' => $product['size'],
            ]);
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::findOrFail($id);

        $order_details = OrdersProduct::join('products','products.id','orders_products.product_id')
            ->select('orders_products.*', 'products.name')
            ->where('orders_products.order_id','=',$id)
            ->get();

        // echo "<pre>";
        // print_r($order_details);
        // die;

        return view('orders.show', compact('order', 'order_details'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function add_product(Request $request)
    {
        $product_id = $request->product_id;
        $row_count = $request->rowCount;
        $product_details = Product::find($product_id);
        // echo "<pre>";
        // print_r($product_details);
        // die;
        return view('products.partials.product-list', compact('product_details', 'row_count'));
    }

    public function generatePDF(Request $request)
    {
        $order = new Order;
        $order->customer_name = $request->cust_name;
        $order->customer_mobile = $request->cust_mobile;
        $order->total_products = $request->total_qty;
        $order->total_amount = $request->total_price;
        $order->save();

        $uniqueOrderNumber = 'INVOICE - ' . $order->id;

        $order->order_number = $uniqueOrderNumber;
        $order->save();

        foreach ($request->products as $product) {
            DB::table('orders_products')->insert([
                'order_id' => $order->id,
                'product_id' => $product['product_id'],
                'price' => $product['price'],
                'total' => $product['total'],
                'quantity' => $product['quantity'],
                'height' => $product['height'],
                'width' => $product['width'],
            ]);
        }

        $products = OrdersProduct::where('order_id',$order->id)
            ->join('products', 'orders_products.product_id','products.id')
            ->select('products.name as product_name', 'orders_products.*')
            ->get();

        $pdf = Pdf::loadView('pdf.billing_invoice', [
            'order' => $order,
            'products' => $products,
        ]);

        return $pdf->stream('invoice.pdf');
    }

    public function generatePDF_1($id)
    {
        $order = Order::findOrFail($id);
        $products = OrdersProduct::where('order_id',$order['id'])
            ->join('products', 'orders_products.product_id','products.id')
            ->select('products.name as product_name', 'orders_products.*')
            ->get();
        $pdf = Pdf::loadView('pdf.billing_invoice', compact('order', 'products'));
        return response($pdf->output(), 200)->header('Content-Type', 'application/pdf');
    }

    public function dashboard()
    {
        $orders = Order::selectRaw('DATE(created_at) as order_date, COUNT(id) as total_orders, SUM(total_products) as total_products, SUM(total_amount) as total_amount')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->paginate(100);
        return view('orders.dashboard',compact('orders'));
    }
}
