@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.vendor.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.vendor.update", [$vendor->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.vendor.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $vendor->name) }}" required>
               
               
            </div>
            <div class="form-group">
                <label class="required" for="state">{{ trans('cruds.vendor.fields.state') }}</label>
                <input class="form-control {{ $errors->has('state') ? 'is-invalid' : '' }}" type="text" name="state" id="state" value="{{ old('state', $vendor->state) }}" required>
               
               
            </div>
            <div class="form-group">
                <label class="required" for="city">{{ trans('cruds.vendor.fields.city') }}</label>
                <input class="form-control {{ $errors->has('city') ? 'is-invalid' : '' }}" type="text" name="city" id="city" value="{{ old('city',  $vendor->city) }}" required>
                  
            </div>
            <div class="form-group">
                <label class="required" for="pincode">{{ trans('cruds.vendor.fields.pincode') }}</label>
                <input class="form-control {{ $errors->has('pincode') ? 'is-invalid' : '' }}" type="number" name="pincode" id="pincode" value="{{ old('pincode',  $vendor->pincode) }}" required>
                  
            </div>
           
                   
            <div class="form-group">
                <label class="required" for="address">{{ trans('cruds.vendor.fields.address') }}</label>
                <textarea class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" name="address" id="address">{{ old('address',$vendor->address) }}</textarea>
            </div>   
            <div class="form-group">
                <label class="required" for="gst_no">{{ trans('cruds.vendor.fields.gst_no') }}</label>
                <input class="form-control {{ $errors->has('gst_no') ? 'is-invalid' : '' }}" type="text" name="gst_no" id="gst_no" value="{{ old('gst_no', $vendor->gst_no) }}" required>     
            </div>
            <div class="form-group">
                        <label class="required" for="status">{{ trans('cruds.vendor.fields.status') }}</label>
                        <select class="form-control" name="status" id="status">
                            <option value="">Please Select</option>
                            <option value="0"{{ $vendor->status == 0 ? 'selected' : ''}}>Active</option>
                            <option value="1"{{ $vendor->status == 1 ? 'selected' : ''}}>Inactive</option>
                           
                        </select>
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

@endsection