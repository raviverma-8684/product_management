@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
     {{ trans('cruds.recieve_stock.title_singular') }}
    </div>

    <div class="card-body">
    <form action="{{ route('admin.recieve_stock.create','') }}" method="GET">
                <div class="row">
                    <div>
                        <div class="form-group">
                        <label for="po">PO:</label>
                            <div style="display: flex;grid-gap: 10px;">
                                <select class="form-control {{ $errors->has('po') ? 'is-invalid' : '' }}" name="po_id" id="po">
                                <option value="">Please Select</option>
                                @foreach($po as $id => $poItem)
                                    @php
                                    try {
                                        $isSelected = $poItem->id == $rs->first()->po_id;
                                    } catch (Exception $e) {
                                        $isSelected = false;
                                    }
                                    @endphp
                                    <option value="{{ $poItem->id }}" {{ $isSelected ? 'selected' : '' }}>{{ $poItem->id }}</option>
                                @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary"> Load {{ trans('cruds.po.title_singular') }}</button>
                            </div>
                        </div>
                       
                    </div>    
                    
                </div>      
            </form>
            <hr>
            @if(!$rs->isEmpty())
            
            <form method="POST" action="{{ route('admin.recieve_stock.store') }}" enctype="multipart/form-data">
            @csrf
           
            <table class=" table table-bordered table-striped table-hover datatable datatable-Product">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.recieve_stock.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.recieve_stock.fields.product') }}
                        </th>
                        <th>
                            {{ trans('cruds.recieve_stock.fields.sku') }}
                        </th>
                        <th>
                            {{ trans('cruds.recieve_stock.fields.quantity') }}
                        </th>
                        
                        <th>
                            {{ trans('cruds.recieve_stock.fields.recieved_quantity') }}
                        </th>
                        <th>
                            {{ trans('cruds.recieve_stock.fields.pending') }}
                        </th>
                        <th>
                            {{ trans('cruds.recieve_stock.fields.recieve_quantity') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @php $pending = 0; @endphp
                    @foreach($rs as $rs)

                       <tr data-entry-id="{{$rs->id}}">    <!-- esme 47 aahega  --> 
                            @php
                            $pending = $rs->quantity-$rs->total_received;
                            $readonly = ($pending === 0) ? 'readonly' : '';
                            @endphp
                            <td>

                            </td>
                       
                            <td>
                                {{$rs -> index+1 }}
                            </td>
                            <td>
                                {{$rs->Product->name}}
                            </td>
                            <td>
                                {{$rs->Product->sku}}
                            </td>
                            <td>
                                {{$rs->quantity}}
                            </td>
                            <td>
                                {{ $rs->total_received }}
                            </td>
                            <td>
                                    {{ $pending }}
                            </td>
                            
                            <td>
                                <!-- //recieved_quantity fill krne k liye ye code hai  -->

                                
                            <div class="form-group">
                                        <input class="form-control " type="hidden" name="po_id[]" id="po_id" value="{{ old('po_id[]', $rs->po_id) }}" >
                            </div>
                            <div class="form-group">
                                        <input class="form-control " type="hidden" name="order_id[]" id="order_id" value="{{ old('order_id[]', $rs->id) }}" >
                            </div>
                                <div class="form-group">
                                    @if($pending == 0)
                                    <input class="form-control " type="text" value="{{ 0 }}" readonly>
                                    @else
                                <input class="form-control {{ $errors->has('recieved_quantity') ? 'is-invalid' : '' }} received-quantity-input" 
                                    type="number" 
                                    name="recieved_quantity[]" 
                                    id="recieved_quantity"
                                    data-qty="{{ $pending }}"
                                    value="{{ old('recieved_quantity[]', '') }}" 
                                    data-pending="{{ $pending }}">
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach 
                </tbody>
            </table>
   
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
        @endif
        
    </div>
</div>

@endsection

@section('scripts')
<script>
$(document).ready(function () {
    $('.received-quantity-input').keyup(function(){
        var inputField = $(this);
        var availQty = parseInt(inputField.attr('data-qty'));
        var requestQty = parseInt(inputField.val());
        
        if (requestQty > availQty) {
            inputField.val(availQty);
            inputField.css('border', '1px solid red');
        } else {
            inputField.css('border', '');
        }
    });
    
    $('form').submit(function(event) {
        var exceeded = false;
        $('.received-quantity-input').each(function() {
            var availQty = parseInt($(this).attr('data-qty'));
            var requestQty = parseInt($(this).val());
            
            if (requestQty > availQty) {
                exceeded = true;
                $(this).css('border', '1px solid red');
            }
        });
        
        if (exceeded) {
            event.preventDefault();
        }
    });
});
</script>
@endsection
