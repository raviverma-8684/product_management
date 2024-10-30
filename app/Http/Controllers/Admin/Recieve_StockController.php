<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Recieve_Stock;
use App\Models\PO;
use App\Models\Product;
use DB;

class Recieve_StockController extends Controller
{
    public function index()
    {
        $rs = Recieve_Stock::with('PO')->select('po_id', DB::raw('SUM(recieved_quantity) as total_received'))
            ->groupBy('po_id')
            ->get();
    //   dd($rs);
        return view('admin.recieve_stock.index', compact('rs'));
    }

    public function create(Request $request)
    {
        $po = PO::whereIn('status', [0, 1])->get();
        // dd($po);
        // "id" => 43
        // "total_quantity" => "201"
        // "total_price" => "20201"
        // "status" => 1
        // "created_at" => "2023-12-02 09:31:28"
        // "updated_at" => "2023-12-02 09:32:52"

        $input = $request->input();
        $rs = null;
        $check = null;

        if ($input !== null) {
            $check = Recieve_Stock::where('po_id', $input)->first();
            // dd($check);

            if ($check !== null) {
                $rs = Order::with('Product', 'Vendor')
                    ->where('po_id', $input)
                    ->get();
            //   dd($rs);
                // Total Received Quantity Calculation:
                $rs1 = Recieve_Stock::where('po_id', $input)
                    ->select('order_id', DB::raw('SUM(recieved_quantity) as total_received'))
                    ->groupBy('order_id')
                    ->get()
                    ->keyBy('order_id');
                    // dd($rs1);

                $rs = $rs->map(function ($order) use ($rs1) {
                    $receivedQty = $rs1->get($order->id);
                    // dd($receivedQty); 


                    $order->total_received = $receivedQty ? $receivedQty->total_received : 0;
                    return $order;
                });
                // dd($rs);
                
            } else {
                $rs = Order::with('Product', 'Vendor')
                    ->where('po_id', $input)
                    ->get();
          
    //   osne rs k anser he dono value po_item or rs tables ki value dono ko ek sath he pass kr diya hai 

                $rs = $rs->map(function ($order) {
                    $receivedQty = 0;
                    $order->total_received = $receivedQty;
                    return $order;
                });
            }
        }

        return view('admin.recieve_stock.create', compact('po', 'rs', 'check')); 
    }

    public function store(Request $request)
    {
        $requestedData = $request->all();
        // dd($requestedData);
   
    //     "po_id" => array:1 [▼
    //       0 => "41"
    //     ]
    //     "order_id" => array:1 [▼
    //       0 => "47"
    //     ]
    //     "recieved_quantity" => array:1 [▼
    //       0 => "8"
    //     ]

        
    //   ]


      // रिकॉर्ड्स को स्टोर करने के लिए एक खाली एरे
        $ordersData = [];
      
     // यदि विन्यास डेटा एक एरे में है
    //  is array use is jo value aa rhi hai wo array format mai hai ya nhi 

        if (is_array($requestedData['order_id'])) {

            foreach ($requestedData['order_id'] as $key => $orderid) {
                
                // isset check kr si variable me value h ya koni

                if (isset($requestedData['recieved_quantity'][$key])) {
                    $orderData = [
                        'po_id' => $requestedData['po_id'][$key],  //dd("41" )
                        'order_id' => $orderid, //dd("47")
                        'recieved_quantity' => $requestedData['recieved_quantity'][$key],//dd(8)
                    ];

                    $ordersData[] = $orderData;
                    // dd($orderData);
                    // "po_id" => "41"
                    // "order_id" => "47"
                    // "recieved_quantity" => "8"
                    
                 
                    $order = Order::where('id', $orderData['order_id'])->select('product_id')->first();
                    //order po_items hai or oski id  or requestData mai order_id ka milan 
                    // dd($order); //"product_id" (find) 
                    $quantity = $order->Product->quantity + $orderData['recieved_quantity'];
                    dd($quantity);// po_items se product_id nikali or fir product tabel se oski qty nikali fir osme add (+) kiya jo request k ander qty aa rhi hai osko 
                    $order->Product->update(['quantity' => $quantity]);// update kr diya product tabel ki qty ko 

                    if ($quantity > 0) {
                        $order->Product->update(['is_in_stock' => 0]);
                    } 

                    // insert data into rec.stock tabels

                    if (!empty($ordersData)) {
                        $rs =  Recieve_Stock::create(array_merge($orderData));
  
                         //rec stock tabel mai order_id  k sare rec.qty ka  sum 
                        $rs1 = Recieve_Stock::with('PO')
                            ->select('po_id', DB::raw('SUM(recieved_quantity) as total_received'))
                            ->groupBy('po_id')
                            ->where('po_id', $rs->po_id)
                            ->first();
                        
                            
                        if ($rs1->PO->total_quantity > $rs1->total_received) {
                            $rs1->PO->update(['status' => 1]);
                        }

                        if ($rs1->PO->total_quantity == $rs1->total_received) {
                            $rs1->PO->update(['status' => 2]);
                        }
                    }
                }
            }
        }

        return redirect()->route('admin.recieve_stock.index');
    }

    public function show(Recieve_Stock $rs, $id)
    {
        $rs = Recieve_Stock::with(['Order'])
            ->where('po_id', $id)
            ->select('order_id', DB::raw('SUM(recieved_quantity) as total_received'))
            ->groupBy('order_id')
            ->get();

        $rs1 = Recieve_Stock::with('PO')
            ->select('po_id', DB::raw('SUM(recieved_quantity) as total_received'))
            ->groupBy('po_id')
            ->where('po_id', $id)
            ->get();

        return view('admin.recieve_stock.show', compact('rs', 'rs1'));
    }

    public function destroy(Recieve_Stock $rs, $id)
    {
        $rs->where('po_id', $id)->delete();
        return back();
    }

    public function massDestroy(Request $request)
    {
        Recieve_Stock::whereIn('id', request('ids'))->delete();
    }
}
