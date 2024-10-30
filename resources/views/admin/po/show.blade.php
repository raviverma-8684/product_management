@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.po.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.po.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class=" table table-bordered table-striped table-hover datatable datatable-Product">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.po.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.po.fields.product') }}
                        </th>
                        <th>
                            {{ trans('cruds.po.fields.vendor') }}
                        </th>
                        <th>
                            {{ trans('cruds.po.fields.quantity') }}
                        </th>
                        <th>
                            {{ trans('cruds.po.fields.price') }}
                        </th>
                        <th>
                            {{ trans('cruds.po.fields.total_price') }}
                        </th>
                       
                        
                       
                    </tr>
                </thead>
                <tbody>
                    @foreach($order as $key => $order)
                        <tr data-entry-id="{{ $order->id }}">
                            <td>

                            </td>
                       
                            <td>
                                {{$loop -> index+1 }}
                            </td>
                            <td>
                                {{$order->Product->name}}
                            </td>
                            <td>
                                {{$order->Vendor->name}}
                            </td>
                            <td>
                                {{$order->quantity}}
                            </td>
                            <td>
                                {{$order->price}}
                            </td>
                            <td>
                                {{$order->total_price}}
                            </td>
                           
                            
                           

                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.po.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection