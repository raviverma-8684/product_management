@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.recieve_stock.title') }}
    </div>

    <div class="card-body">
        <div class="card-header">
            
            <table class="table table-bordered table-striped">
                <tbody>
                @foreach($rs1 as $rs1)

                    <tr>
                        <th>
                            {{ trans('cruds.recieve_stock.fields.po_id') }}
                        </th>
                        <td>
                            {{ $rs1->po_id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.recieve_stock.fields.total_quantity') }}
                        </th>
                        <td>
                            {{ $rs1->PO->total_quantity }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.recieve_stock.fields.recieved_quantity') }}
                        </th>
                        <td>
                            {{ $rs1->total_received }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            
        </div>
        <div class="form-group">
           
        <table class=" table table-bordered table-striped table-hover datatable datatable-Product">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.recieve_stock.fields.sn') }}
                        </th>
                        <th>
                            {{ trans('cruds.recieve_stock.fields.product') }}
                        </th>
                        <th>
                            {{ trans('cruds.recieve_stock.fields.sku') }}
                        </th>
                        <th>
                            {{ trans('cruds.recieve_stock.fields.vendor') }}
                        </th>
                        <th>
                            {{ trans('cruds.recieve_stock.fields.quantity') }}
                        </th>
                        <th>
                            {{ trans('cruds.recieve_stock.fields.recieved_quantity') }}
                        </th>
                       
                        
                    </tr>
                </thead>
                <tbody>
                @foreach($rs as $rs)
                        <tr>
                            <td></td>
                            <td>
                                {{$loop -> index+1 }}
                            </td>
                            <td>
                                {{ $rs->Order->Product->name }}
                            </td>
                            <td>
                                {{ $rs->Order->Product->sku }}
                            </td>
                            <td>
                                {{ $rs->Order->Vendor->name }}
                            </td>
                            <td>
                                {{ $rs->Order->quantity }}
                            </td>

                                
                          
                            <td>
                                {{$rs->total_received}}
                            </td>

                        </tr>
                   @endforeach
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.recieve_stock.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection