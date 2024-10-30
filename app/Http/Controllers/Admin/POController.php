<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Recieve_Stock;
use Illuminate\Http\Request;
use App\Models\PO;
use App\Models\Product;
use App\Models\Vendor;



class POController extends Controller
{
    public function index()
    {
        $po = PO::all();
        // dd($po);
        $order = Order::all();
    
        
         
    
        return view('admin.po.index', compact('po', 'order'));
    }
    
    
    public function create()
    { 
        $product = Product::pluck('name', 'id');
        $vendor = Vendor::pluck('name', 'id');
    
        
        return view('admin.po.create', compact('product','vendor'));
    }
    public function store(Request $request)
    {
      $requestedData = $request->all();
    //  dd($requestedData);
      $po = PO::create([
        
        'total_price' => array_sum($requestedData['total_price']),
        'total_quantity' => array_sum($requestedData['quantity']),
        'status'=> 0,
    ]);
    //    dd($po);
        $requestedData['po_id'] = $po->id;
    
        // Loop through the duplicated fields and save each as an Order record
        $ordersData = [];
        foreach ($requestedData['product_id'] as $key => $productId) {  //0($key) index pr kya aa rha hai 
            $orderData = [ 
                'po_id' => $requestedData['po_id'],
                'product_id' => $productId,
                'vendor_id' => $requestedData['vendor_id'][$key],
                'quantity' => $requestedData['quantity'][$key],
                'price' => $requestedData['price'][$key],
                'total_price' => $requestedData['total_price'][$key],
            ];
            $ordersData[] = $orderData;
          
        }
       
         
        // Create multiple Order records using mass assignment
        Order::insert($ordersData);
    
        return redirect()->route('admin.po.index');
    }
    public function show(PO $po)
    {
        // dd($po);
      $order = Order::with('Product', 'Vendor')->where('po_id', $po->id)->get();
    //    $order->load('Product','Vendor','PO');
       


        return view('admin.po.show', compact('order','po'));
    }
    public function edit(PO $po)
    {
        // Find the Purchase Order by its ID
        $order = Order::where('po_id',$po->id)->get(); 
        // dd($order);
//    echo"<pre>";print_r($order);die();
       
        $product = Product::pluck('name', 'id'); 
        $vendor = Vendor::pluck('name', 'id'); 

        return view('admin.po.edit', compact('po', 'product', 'vendor','order'));
    }
    public function update(Request $request, PO $po)
{
    $requestedData = $request->all();
    //echo"<pre>";print_r($requestedData);die();
    $po->update([
        'total_price' => array_sum($requestedData['total_price']),
        'total_quantity' => array_sum($requestedData['quantity']),
    ]);

    // Update or create Order records based on the product IDs
    foreach ($requestedData['product_id'] as $key => $productId) {
             
        $orderData = [
            
            'product_id' => $productId,
            'vendor_id' => $requestedData['vendor_id'][$key],
            'quantity' => $requestedData['quantity'][$key],
            'price' => $requestedData['price'][$key],
            'total_price' => $requestedData['total_price'][$key],
        ];
        // echo"<pre>";print_r($requestedData['id'],);die();
        
        if (!empty($requestedData['id'][$key])) {
            $existingOrderId = $requestedData['id'][$key];
            $existingOrder = Order::find($existingOrderId);
            
            if ($existingOrder) {
                $existingOrder->update($orderData);
            }
        } else {
            // If no existing order ID, create a new order
            Order::create(array_merge($orderData, ['po_id' => $po->id]));
        }
    
    }

    return redirect()->route('admin.po.index');
}
public function destroy(PO $po )
{
    //abort_if(Gate::denies('product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    Order::where('po_id', $po->id)->delete();
    Recieve_Stock::where('po_id', $po->id)->delete();

    $po->delete();

    return back();

}
public function massDestroy(Request $request)
    {

        Order::whereIn('po_id', request('ids'))->delete();
        Recieve_Stock::whereIn('po_id', request('ids'))->delete();
        PO::whereIn('id', request('ids'))->delete();

       
    }
    public function close(Request $request,$id){
        
        PO::where('id', $id)->update(['status' => 3]);
        return redirect()->route('admin.po.index');
    }
    public function cancel(Request $request,$id){
        PO::where('id',$id)->update(['status' => 4]);
        return redirect()->route('admin.po.index');
    }
}
