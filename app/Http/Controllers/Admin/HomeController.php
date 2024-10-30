<?php

namespace App\Http\Controllers\Admin;
use App\Models\Product;
use App\Models\User;
use App\Models\Role;
use App\Models\Vendor;

class HomeController
{
    public function index()
    {
        $productCount = Product::count();
        $vendorCount = Vendor::count();
        //$users = User::with(['roles'])->get();
        //echo"<pre>";print_r($users->roles);die();
        return view('home',compact('productCount','vendorCount'));
    }
}
