<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyProductRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;

use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        $product = Product::all();
        return view('admin.products.index', compact('product'));
    }

    public function create()
    {


        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $file = $request->photo;
      $fileName = '/storage/productimage/' . rand() . '.' . $file->extension();
      $destinationPath = public_path() . '/storage/productimage/';
      $file->move($destinationPath, $fileName);
      $is_true =  Product::create(array_merge($request->all(), ['photo' => $fileName]));
        return redirect()->route('admin.products.index');
    }

    public function edit(Product $product)
    {
        

        

        return view('admin.products.edit', compact( 'product'));
    }

    public function update(Request $request, Product $product)
    {
        if ($request->photo) {
            $file = $request->photo;
            $fileName = '/storage/productimage/' . rand() . '.' . $file->extension();
            $destinationPath = public_path() . '/storage/productimage/';
            $file->move($destinationPath, $fileName);
        } else {
            $fileName = $product->photo;
        }
        $product->update(array_merge($request->all(), ['photo' => $fileName]));

        return redirect()->route('admin.products.index');
    }

    public function show(Product $product)
    {

        return view('admin.products.show', compact('product'));
    }

    public function destroy(Product $product)
    {
       

        $product->delete();

        return back();
    }

    public function massDestroy(MassDestroyProductRequest $request)
    {
        Product::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

   
}
