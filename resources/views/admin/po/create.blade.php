@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.add') }} {{ trans('cruds.po.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.po.store") }}" enctype="multipart/form-data">
            @csrf
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>{{ trans('cruds.po.fields.product') }}</th>
                        <th>{{ trans('cruds.po.fields.vendor') }}</th>
                        <th>{{ trans('cruds.po.fields.quantity') }}</th>
                        <th>{{ trans('cruds.po.fields.price') }}</th>
                        <th>{{ trans('cruds.po.fields.total_price') }}</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="duplicate">
                    <tr>
                        <td>
                            <div class="form-group">
                                <select class="form-control select2" name="product_id[]" id="product_id0">
                                    <option value="">Please Select</option>
                                    @foreach($product as $id => $productname)
                                        <option value="{{ $id }}">{{ $productname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <select class="form-control select2" name="vendor_id[]" id="vendor_id0">
                                    <option value="">Please Select</option>
                                    @foreach($vendor as $id => $vendorName)
                                        <option value="{{ $id }}">{{ $vendorName }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input class="form-control quantity" type="number" name="quantity[]" id="quantity0" required>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input class="form-control price" type="number" name="price[]" id="price0" required>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input class="form-control total_price" type="number" name="total_price[]" id="total_price0" required>
                            </div>
                        </td>
                        <td>
                            <!-- Remove button, initially disabled -->
                            <button type="button" class="btn btn-danger remove-sub-menu-btn" disabled>Remove</button>
                        </td>
                    </tr>
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
var count = 0;

$('.add-sub-menu-btn').click(function() {
    count++;
    var subMenuTemplate = `
    <tr>
        <td>
            <div class="form-group">
                <select class="form-control select2" name="product_id[]" id="product_id${count}">
                    <option value="">Please Select</option>
                    @foreach($product as $id => $productname)
                        <option value="{{ $id }}">{{ $productname }}</option>
                    @endforeach
                </select>
            </div>
        </td>
        <td>
            <div class="form-group">
                <select class="form-control select2" name="vendor_id[]" id="vendor_id${count}">
                    <option value="">Please Select</option>
                    @foreach($vendor as $id => $vendorName)
                        <option value="{{ $id }}">{{ $vendorName }}</option>
                    @endforeach
                </select>
            </div>
        </td>
        <td>
            <div class="form-group">
                <input class="form-control quantity" type="number" name="quantity[]" id="quantity${count}" required>
            </div>
        </td>
        <td>
            <div class="form-group">
                <input class="form-control price" type="number" name="price[]" id="price${count}" required>
            </div>
        </td>
        <td>
            <div class="form-group">
                <input class="form-control total_price" type="number" name="total_price[]" id="total_price${count}" required>
            </div>
        </td>
        <td>
            <!-- Remove button, initially enabled -->
            <button type="button" class="btn btn-danger remove-sub-menu-btn">Remove</button>
        </td>
    </tr>
    `;

    // Append the new row to the table
    $('#duplicate').append(subMenuTemplate);

    // Enable the "Remove" button for the previous row
    $('.remove-sub-menu-btn').prop('disabled', false);
});

// Calculate total price for each row on input change
$(document).on('change', '.quantity, .price', function() {
    var rowId = $(this).attr('id').match(/\d+/)[0];
    calculateTotalPrice(rowId);
});

// Function to calculate total price
function calculateTotalPrice(rowId) {
    var quantity = parseFloat($('#quantity' + rowId).val()) || 0;
    var price = parseFloat($('#price' + rowId).val()) || 0;
    var total_price = quantity * price;
    $('#total_price' + rowId).val(total_price.toFixed(2));
}

// Remove button click event
$(document).on('click', '.remove-sub-menu-btn', function() {
    var rowId = $(this).closest('tr').find('td:eq(0) select').attr('id').match(/\d+/)[0];
    $(this).closest('tr').remove();

    // Disable the "Remove" button for the last row
    if ($('#duplicate tr').length === 1) {
        $('#duplicate tr').find('.remove-sub-menu-btn').prop('disabled', true);
    }

    // Recalculate total price for the remaining rows
    calculateRemainingTotalPrices();
});

// Function to recalculate total prices for the remaining rows
function calculateRemainingTotalPrices() {
    $('#duplicate tr').each(function(index) {
        var rowId = $(this).find('td:eq(0) select').attr('id').match(/\d+/)[0];
        calculateTotalPrice(rowId);
    });
}
</script>  
@endsection
