@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.sell_stock.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.sell_stock.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class=" table table-bordered table-striped table-hover datatable datatable-Product">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.sell_stock.fields.sn') }}
                        </th>
                        <th>
                            {{ trans('cruds.sell_stock.fields.product_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.sell_stock.fields.selling_price') }}
                        </th>
                        
                        <th>
                            {{ trans('cruds.sell_stock.fields.ordered_quantity') }}
                        </th>
                        <th>
                            {{ trans('cruds.sell_stock.fields.discount_type') }}
                        </th>
                        <th>
                            {{ trans('cruds.sell_stock.fields.discount') }}
                        </th>
                        
                        <th>
                            {{ trans('cruds.sell_stock.fields.total_selling_price') }}
                        </th>
                       
                        
                       
                    </tr>
                </thead>
                <tbody>
                    @foreach($sell_stock_items as $key => $val)
                        <tr data-entry-id="{{ $val->id }}">
                            <td>

                            </td>
                       
                            <td>
                                {{$loop -> index+1 }}
                            </td>
                            <td>
                                {{$val->Product->name}}
                            </td>
                            <td>
                                {{$val->Product->selling_price}}
                            </td>
                            <td>
                                {{$val->ordered_quantity}}
                            </td>
                            <td>
                                {{$val->discount_type ? 'Flat' : 'Percentage'}}
                            </td>
                            <td>
                                {{$val->discount}}
                            </td>
                            
                            <td>
                                {{$val->total_selling_price}}
                            </td>
                           
                            
                           

                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.sell_stock.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection