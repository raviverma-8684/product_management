@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.add') }} {{ trans('cruds.product.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.products.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.product.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
               
               
            </div>
            <div class="form-group">
                <label class="required" for="sku">{{ trans('cruds.product.fields.sku') }}</label>
                <input class="form-control {{ $errors->has('sku') ? 'is-invalid' : '' }}" type="text" name="sku" id="sku" value="{{ old('sku', '') }}" required>
               
               
            </div>
            <div class="form-group">
                <label class="required" for="quantity">{{ trans('cruds.product.fields.quantity') }}</label>
                <input class="form-control {{ $errors->has('quantity') ? 'is-invalid' : '' }}" type="number" name="quantity" id="quantity" value="{{ old('quantity', '') }}" required>
                  
            </div>
           
                    <div class="form-group">
                        <label class="required" for="is_in_stock">{{ trans('cruds.product.fields.is_in_stock') }}</label>
                        <select class="form-control" name="is_in_stock" id="is_in_stock">
                            <option value="">Please Select</option>
                            <option value="0">In Stock</option>
                            <option value="1">Out Of Stock</option>
                           
                        </select>
                    </div>
            <div class="form-group">
                <label class="required" for="selling_price">{{ trans('cruds.product.fields.selling_price') }}</label>
                <input class="form-control {{ $errors->has('selling_price') ? 'is-invalid' : '' }}" type="number" name="selling_price" id="selling_price" value="{{ old('selling_price', '') }}" required>     
            </div>

            <div class="form-group">
                <label class="required" for="gst_rate">{{ trans('cruds.product.fields.gst_rate') }}</label>
                <input class="form-control {{ $errors->has('gst_rate') ? 'is-invalid' : '' }}" type="number" name="gst_rate" id="gst_rate" value="{{ old('gst_rate', '') }}" required>     
            </div>

            <div class="form-group">
                        <label class="required" for="status">{{ trans('cruds.product.fields.status') }}</label>
                        <select class="form-control" name="status" id="status">
                            <option value="">Please Select</option>
                            <option value="0">Active</option>
                            <option value="1">Inactive</option>
                           
                        </select>
                    </div>

            
            
                <div class="form-group">
                        <label class="required" for="photo">{{ trans('cruds.product.fields.photo') }}</label>
                        <div id="preview"></div>
                        <input id="photo" type="file" name="photo" value="{{ old('photo', '') }}" required>
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