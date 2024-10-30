<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;



class VendorController extends Controller
{
    public function index()
    {
      

        $vendor = Vendor::all();

        return view('admin.vendor.index', compact('vendor'));
    }

    public function create()
    {
        

        

        return view('admin.vendor.create');
    }

    public function store(Request $request)
    {
        Vendor::create($request->all());
        return redirect()->route('admin.vendor.index');
    }

    public function edit(Vendor $vendor)
    {
        

        

        return view('admin.vendor.edit', compact( 'vendor'));
    }

    public function update(Request $request, Vendor $vendor)
    {
        $vendor->update($request->all());
        return redirect()->route('admin.products.index');
    }

    public function show(Vendor $vendor)
    {

        return view('admin.vendor.show', compact('vendor'));
    }

    public function destroy(Vendor $vendor)
    {
       

        $vendor->delete();

        return back();
    }

    public function massDestroy(Request $request)
    {
        Vendor::whereIn('id', request('ids'))->delete();

        return response(null);
    }
}
