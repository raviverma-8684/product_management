<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sell_Stock;
use App\Models\Sell_Stock_Items;
use App\Models\Product;
use DB;

class Sell_StockController extends Controller
{
    public function index()
    {
        $sell_stock = Sell_Stock::all();
    
        return view('admin.sell_stock.index', compact('sell_stock'));
    }
    public function create()
    {
        $product = Product::pluck('name', 'id');
       
        
        return view('admin.sell_stock.create', compact('product'));
    }
    public function getproduct(Request $request)
    {
        
        $getData = Product::where('id', $request->id)->select('quantity','selling_price')->first();
        $response = ['status' => false, 'data' => []];
        if (!empty($getData)) {
            $response = ['status' => true, 'data' => $getData];
            return $response;
        } else {
            return $response;
        }

    }
    public function store(Request $request)
    {
      $requestedData = $request->all();
      $sell_stock = Sell_Stock::create([
        'total_ordered_quantity' => array_sum($requestedData['ordered_quantity']),
        'total_ordered_price' => array_sum($requestedData['total_selling_price']),
       
    ]);
        
        $requestedData['sell_stock_id'] = $sell_stock->id;
    
        // Loop through the duplicated fields and save each as an Order record
        $ordersData = [];
        foreach ($requestedData['product_id'] as $key => $productId) {
            $orderData = [
                'sell_stock_id' => $requestedData['sell_stock_id'],
                'product_id' => $productId,
                'ordered_quantity' => $requestedData['ordered_quantity'][$key],
                'discount_type' => $requestedData['discount_type'][$key],
                'discount' => $requestedData['discount'][$key],
                'total_selling_price' => $requestedData['total_selling_price'][$key],
            ];
            $ordersData[] = $orderData;
            $product = Product::where('id', $productId)->first();
                    $quantity = $product->quantity - $orderData['ordered_quantity'];
                    $product->update(['quantity' => $quantity]);
                    if($product->quantity == 0){
                        $product->update(['is_in_stock' => 1]);
                    }
          
        }
       
         
        // Create multiple Order records using mass assignment
        Sell_Stock_Items::insert($ordersData);
    
        return redirect()->route('admin.sell_stock.index');
    }
    public function update(Request $request, Sell_Stock $sell_stock)
    {
        $requestedData = $request->all();
        //echo"<pre>";print_r($requestedData);die();
        $sell_stock->update([
            'total_ordered_quantity' => array_sum($requestedData['ordered_quantity']),
            'total_ordered_price' => array_sum($requestedData['total_selling_price']),
        ]);
    
        // Update or create Order records based on the product IDs
        foreach ($requestedData['product_id'] as $key => $productId) {
                 
            $orderData = [
                
                'product_id' => $productId,
                'ordered_quantity' => $requestedData['ordered_quantity'][$key],
                'discount_type' => $requestedData['discount_type'][$key],
                'discount' => $requestedData['discount'][$key],
                'total_selling_price' => $requestedData['total_selling_price'][$key],
            ];
            // echo"<pre>";print_r($requestedData['id'],);die();
            
            if (!empty($requestedData['id'][$key])) {
                $existing_sell_stock_Id = $requestedData['id'][$key];
                $existing_sell_stock = Sell_Stock_Items::where('id',$existing_sell_stock_Id)->first();
               
                
                if ($existing_sell_stock) {
                    if($existing_sell_stock->ordered_quantity <= $requestedData['ordered_quantity'][$key]){
                        $orderData1 = $requestedData['ordered_quantity'][$key] - $existing_sell_stock->ordered_quantity;
                        $quantity = $existing_sell_stock->Product->quantity - $orderData1;
                        $existing_sell_stock->Product->update(['quantity' => $quantity]);
                        if($existing_sell_stock->product->quantity == 0){
                            $existing_sell_stock->product->update(['is_in_stock' => 1]);
                        }
                         
                    }
                    elseif($existing_sell_stock->ordered_quantity > $requestedData['ordered_quantity'][$key]){
                        $orderData1 =  $existing_sell_stock->ordered_quantity - $requestedData['ordered_quantity'][$key] ;
                        $quantity = $existing_sell_stock->Product->quantity + $orderData1;
                        $existing_sell_stock->Product->update(['quantity' => $quantity]);
                        if($existing_sell_stock->product->quantity == 0){
                            $existing_sell_stock->product->update(['is_in_stock' => 1]);
                        }
                    }
                    $existing_sell_stock->update($orderData);
                }
            } else {
                        $quantity = $existing_sell_stock->Product->quantity - $requestedData['ordered_quantity'][$key] ;
                        $existing_sell_stock->Product->update(['quantity' => $quantity]);
                        if($existing_sell_stock->product->quantity == 0){
                            $existing_sell_stock->product->update(['is_in_stock' => 1]);
                        }
                // If no existing order ID, create a new order
                Sell_Stock_Items::create(array_merge($orderData, ['sell_stock_id' => $sell_stock->id]));
            }
        
        }
        $existingIds = $requestedData['id']; // Assuming 'id' is the primary key of Sell_Stock_Items table

// Store IDs that should be deleted in the $idsToDelete array
$idsToDelete = Sell_Stock_Items::whereNotIn('id', $existingIds)->pluck('id')->toArray();

// Delete records that are not present in the requested data


// Add the deleted IDs to the product quantity
foreach ($idsToDelete as $deletedId) {
    $deletedItem = Sell_Stock_Items::find($deletedId);
    if ($deletedItem) {
        $quantityToAdd = $deletedItem->ordered_quantity;
        $product = $deletedItem->Product;
        $newQuantity = $product->quantity + $quantityToAdd;
        $product->update(['quantity' => $newQuantity]);
    }
}
Sell_Stock_Items::whereIn('id', $idsToDelete)->delete();
        return redirect()->route('admin.sell_stock.index');
    }
    public function edit(Sell_Stock $sell_stock)
    {
       
        $sell_stock_items = Sell_Stock_Items::where('sell_stock_id',$sell_stock->id)->get();
        $product = Product::pluck('name', 'id');
   
       
        
         

        return view('admin.sell_stock.edit', compact('sell_stock','sell_stock_items','product'));
    }
    public function show(Sell_Stock $sell_stock)
    {
      $sell_stock_items = Sell_Stock_Items::with('Product')->where('sell_stock_id', $sell_stock->id)->get();
       


        return view('admin.sell_stock.show', compact('sell_stock','sell_stock_items'));
    }
    
    public function destroy(Sell_Stock $sell_stock )
{   $idsToDelete = Sell_Stock_Items::where('sell_stock_id', $sell_stock->id)->pluck('id')->toArray();
    foreach ($idsToDelete as $deletedId) {
        $deletedItem = Sell_Stock_Items::find($deletedId);
        if ($deletedItem) {
            $quantityToAdd = $deletedItem->ordered_quantity;
            $product = $deletedItem->Product;
            $newQuantity = $product->quantity + $quantityToAdd;
            $product->update(['quantity' => $newQuantity]);
        }
    }
    //abort_if(Gate::denies('product_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    Sell_Stock_Items::where('sell_stock_id', $sell_stock->id)->delete();
   

    $sell_stock->delete();

    return back();

}
public function massDestroy(Request $request)
    {
        $idsToDelete = Sell_Stock_Items::where('sell_stock_id',  request('ids'))->pluck('id')->toArray();
        foreach ($idsToDelete as $deletedId) {
            $deletedItem = Sell_Stock_Items::find($deletedId);
            if ($deletedItem) {
                $quantityToAdd = $deletedItem->ordered_quantity;
                $product = $deletedItem->Product;
                $newQuantity = $product->quantity + $quantityToAdd;
                $product->update(['quantity' => $newQuantity]);
            }
        }

        Sell_Stock_Items::whereIn('po_id', request('ids'))->delete();
       
        Sell_Stock::whereIn('id', request('ids'))->delete();

       
    }
    

}
