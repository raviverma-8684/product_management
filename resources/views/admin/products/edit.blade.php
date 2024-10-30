@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.product.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.products.update", [$product->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.product.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required>
               
               
            </div>
            <div class="form-group">
                <label class="required" for="sku">{{ trans('cruds.product.fields.sku') }}</label>
                <input class="form-control {{ $errors->has('sku') ? 'is-invalid' : '' }}" type="text" name="sku" id="sku" value="{{ old('sku',  $product->sku) }}" required>
               
               
            </div>
            <div class="form-group">
                <label class="required" for="quantity">{{ trans('cruds.product.fields.quantity') }}</label>
                <input class="form-control {{ $errors->has('quantity') ? 'is-invalid' : '' }}" type="number" name="quantity" id="quantity" value="{{ old('quantity', $product->quantity) }}" required>
                  
            </div>
           
                    <div class="form-group">
                        <label class="required" for="is_in_stock">{{ trans('cruds.product.fields.is_in_stock') }}</label>
                        <select class="form-control" name="is_in_stock" id="is_in_stock">
                            <option value="">Please Select</option>
                            <option value="0"{{ $product->is_in_stock == 0 ? 'selected' : ''}}>In Stock</option>
                            <option value="1" {{ $product->is_in_stock == 1 ? 'selected' : ''}}>Out Of Stock</option>
                           
                        </select>
                    </div>
            <div class="form-group">
                <label class="required" for="selling_price">{{ trans('cruds.product.fields.selling_price') }}</label>
                <input class="form-control {{ $errors->has('selling_price') ? 'is-invalid' : '' }}" type="number" name="selling_price" id="selling_price" value="{{ old('selling_price',  $product->selling_price) }}" required>     
            </div>
            <div class="form-group">
                        <label class="required" for="status">{{ trans('cruds.product.fields.status') }}</label>
                        <select class="form-control" name="status" id="status">
                            <option value="">Please Select</option>
                            <option value="0"{{ $product->status == 0 ? 'selected' : ''}}>Active</option>
                            <option value="1"{{ $product->status == 1 ? 'selected' : ''}}>Inactive</option>
                           
                        </select>
                    </div>

            
            
                    <div class="form-group">
                        <label class="required" for="photo">{{ trans('cruds.product.fields.photo') }}</label>
                        <div id="preview">
                          <img src="{{ asset($product->photo) }}" width="50px" id="selectedImage">
                        </div>
                       
                      
                        <input class="form-control {{ $errors->has('photo') ? 'is-invalid' : '' }}" type="file" name="photo" id="photo" value="{{ old('photo', $product->photo) }}" >
                    </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection

@section('scripts')
<script>
  document.getElementById('photo').addEventListener('change', function(e) {
        var file = e.target.files[0];
        var reader = new FileReader();

        reader.onload = function(e) {
            var preview = document.getElementById('preview');
            var img = document.createElement('img');
            img.src = e.target.result;
            img.style.maxWidth = '100%';
            preview.innerHTML = '';
            preview.appendChild(img);

            var removeBtn = document.createElement('button');
            removeBtn.innerText = 'Remove';
            removeBtn.addEventListener('click', function() {
                preview.innerHTML = '';
                document.getElementById('photo').value = ''; // Reset the file input field
            });
            preview.appendChild(removeBtn);
        };

        if (file) {
            reader.readAsDataURL(file);
        }
    });
</script>
<style>
    #preview {
        max-width: 100px;
        margin-top: 10px;
    }
</style>
@endsection