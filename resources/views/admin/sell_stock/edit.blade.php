@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('cruds.sell_stock.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.sell_stock.update', $sell_stock->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <table class="table table-bordered table-striped table-hover datatable datatable-Product">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>{{ trans('cruds.sell_stock.fields.product_name') }}</th>
                        <th>{{ trans('cruds.sell_stock.fields.available_quantity') }}</th>
                        <th>{{ trans('cruds.sell_stock.fields.selling_price') }}</th>
                        <th>{{ trans('cruds.sell_stock.fields.ordered_quantity') }}</th>
                        <th>{{ trans('cruds.sell_stock.fields.discount_type') }}</th>
                        <th>{{ trans('cruds.sell_stock.fields.discount') }}</th>
                        <th>{{ trans('cruds.sell_stock.fields.total_selling_price') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="duplicate">
                    @foreach($sell_stock_items as $val)
                    <tr>
                        <td></td>
                        <td>
                        <input class="form-control {{ $errors->has('quantity') ? 'is-invalid' : '' }}" type="hidden" name="id[]" id="id" value="{{ old('id[]', $val->id) }}" required>
                            <div class="form-group">
                                <select class="form-control select2" name="product_id[]" id="product_id{{ $loop->index }}">
                                    <option value="">Please Select</option>
                                    @foreach($product as $id => $productname)
                                        <option value="{{ $id }}" {{ $val->product_id == $id ? 'selected' : '' }}>{{ $productname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input class="form-control" type="number" name="quantity[]" id="quantity{{ $loop->index }}" value="{{$val->Product->quantity}}" required >
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input class="form-control" type="number" name="selling_price[]" id="selling_price{{ $loop->index }}" value="{{$val->Product->selling_price}}"required >
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input class="form-control" type="number" name="ordered_quantity[]" id="ordered_quantity{{ $loop->index }}" required value="{{ $val->ordered_quantity }}">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <select class="form-control select2" name="discount_type[]" id="discount_type{{ $loop->index }}">
                                    <option value="">Please Select</option>
                                    <option value="0" {{ $val->discount_type == 0 ? 'selected' : '' }}>Percentage</option>
                                    <option value="1" {{ $val->discount_type == 1 ? 'selected' : '' }}>Flat</option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input class="form-control" type="number" name="discount[]" id="discount{{ $loop->index }}" required value="{{ $val->discount }}">
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input class="form-control" type="number" name="total_selling_price[]" id="total_selling_price{{ $loop->index }}" required value="">
                            </div>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger remove-sub-menu-btn mt-2">Remove</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <button type="button" class="btn btn-success add-sub-menu-btn">Add More..</button>

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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
// Initialize count based on existing rows
var count = {{ count($sell_stock_items) - 1 }};

// Function to handle adding new rows
$('.add-sub-menu-btn').click(function() {
    count++;
    var subMenuTemplate = `
    <tr>
        <td></td>
        <td>
            <div class="form-group">
                <select class="form-control select2" name="product_id[]" id="product_id${count}" required>
                    <option value="">Please Select</option>
                    @foreach($product as $id => $productname)
                        <option value="{{ $id }}">{{ $productname }}</option>
                    @endforeach
                </select>
            </div>
        </td>
        <td>
            <div class="form-group">
                <input class="form-control" type="number" name="quantity[]" id="quantity${count}" required>
            </div>
        </td>
        <td>
            <div class="form-group">
                <input class="form-control" type="number" name="selling_price[]" id="selling_price${count}" required>
            </div>
        </td>
        <td>
            <div class="form-group">
                <input class="form-control" type="number" name="ordered_quantity[]" id="ordered_quantity${count}" required>
            </div>
        </td>
        <td>
            <div class="form-group">
                <select class="form-control select2" name="discount_type[]" id="discount_type${count}">
                    <option value="">Please Select</option>
                    <option value="0">Percentage</option>
                    <option value="1">Flat</option>
                </select>
            </div>
        </td>
        <td>
            <div class="form-group">
                <input class="form-control" type="number" name="discount[]" id="discount${count}" required>
            </div>
        </td>
        <td>
            <div class="form-group">
                <input class="form-control" type="number" name="total_selling_price[]" id="total_selling_price${count}" required>
            </div>
        </td>
        <td>
            <button type="button" class="btn btn-danger remove-sub-menu-btn mt-2" ${count === 0 ? 'disabled' : ''}>Remove</button>
        </td>
    </tr>
    `;

    // Append the new row to the table
    $('#duplicate').append(subMenuTemplate);

    // Enable the Remove button for all rows except the last one
    $('.remove-sub-menu-btn').prop('disabled', false);
});

// Function to handle removing rows
$(document).on('click', '.remove-sub-menu-btn', function() {
    if (count > 0) {
        $(this).closest('tr').remove();
        count--;

        // Disable the Remove button for the last row
        if (count === 0) {
            $('.remove-sub-menu-btn').last().prop('disabled', true);
        }
    }
});

// Function to calculate total selling price for a row
function calculateTotalSellingPrice(rowId) {
    var discountType = $('#discount_type' + rowId).val();
    var discount = parseFloat($('#discount' + rowId).val());
    var sellingPrice = parseFloat($('#selling_price' + rowId).val());
    var orderedQuantity = parseFloat($('#ordered_quantity' + rowId).val());

    var totalSellingPrice = 0;

    if (discountType == 0) { // Percentage discount
        totalSellingPrice = (sellingPrice * orderedQuantity) - ((discount / 100) * (sellingPrice * orderedQuantity));
    } else if (discountType == 1) { // Flat discount
        totalSellingPrice = (sellingPrice * orderedQuantity) - discount;
    }

    // Update the total selling price field for the current row
    $('#total_selling_price' + rowId).val(totalSellingPrice.toFixed(2));
}

// Add an event listener for relevant input fields in each row using event delegation
$(document).on('change', "[id^='discount_type'], [id^='discount'], [id^='selling_price'], [id^='ordered_quantity']", function() {
    var rowId = $(this).attr('id').match(/\d+/)[0];
    calculateTotalSellingPrice(rowId);
});

// Initial calculation when the page loads
$(document).ready(function() {
    // Calculate the total selling price for all existing rows
    for (var i = 0; i <= count; i++) {
        calculateTotalSellingPrice(i);
    }

    // Disable the Remove button for the last row initially
    $('.remove-sub-menu-btn').last().prop('disabled', true);
});
$("body").on("change", "[id^='product_id']", function() {
    var id = $(this).val();
    var token = '{{ csrf_token() }}';
    var rowId = $(this).attr('id').match(/\d+/)[0];

    $.ajax({
        type: 'post',
        url: '{{ route("admin.getproduct") }}',
        data: {
            _token: token,
            id: id
        },
        dataType: 'json',
        success: function(response) {
            $('#quantity' + rowId).val(response.data.quantity);
            $('#selling_price' + rowId).val(response.data.selling_price);
        },
        error: function(data) {
            console.log(data);
        }
    });
});
</script>

@endsection
