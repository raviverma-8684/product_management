@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.po.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.po.update', $po->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT') <!-- Use PUT method for updates -->

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
                    @foreach($order as $item) <!-- Assuming $po->items contains the related items -->
                    <tr>
                        <td>
                            <div class="form-group">
                                <input class="form-control {{ $errors->has('quantity') ? 'is-invalid' : '' }}" type="hidden" name="id[]" id="id{{ $loop->index }}" value="{{ old('id[]', $item->id) }}" required>
                                <select class="form-control select2" name="product_id[]" id="product_id{{ $loop->index }}">
                                    <option value="">Please Select</option>
                                    @foreach($product as $id => $productname)
                                        <option value="{{ $id }}" {{ $id == $item->product_id ? 'selected' : '' }}>{{ $productname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <select class="form-control select2" name="vendor_id[]" id="vendor_id{{ $loop->index }}">
                                    <option value="">Please Select</option>
                                    @foreach($vendor as $id => $vendorName)
                                        <option value="{{ $id }}" {{ $id == $item->vendor_id ? 'selected' : '' }}>{{ $vendorName }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input class="form-control quantity" type="number" name="quantity[]" id="quantity{{ $loop->index }}" value="{{ $item->quantity }}" required>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input class="form-control price" type="number" name="price[]" id="price{{ $loop->index }}" value="{{ $item->price }}" required>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input class="form-control total_price" type="number" name="total_price[]" id="total_price{{ $loop->index }}" value="{{ $item->total_price }}" required>
                            </div>
                        </td>
                        <td>
                            <!-- Remove button, initially enabled -->
                            <button type="button" class="btn btn-danger remove-sub-menu-btn">Remove</button>
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
var count = {{ count($order) - 1 }};

// Function to handle adding new rows
$('.add-sub-menu-btn').click(function() {
    count++;
    var subMenuTemplate = `
    <tr>
        <td>
            <div class="form-group">
                <input class="form-control {{ $errors->has('quantity') ? 'is-invalid' : '' }}" type="hidden" name="id[]" id="id${count}" value="" required>
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

    // Enable the Remove button for all rows except the last one
    $('.remove-sub-menu-btn').prop('disabled', false);

    // Disable the Remove button for entries with IDs (from the database)
    $('#duplicate tr').each(function(index) {
        if ($(this).find('input[name^="id"]').val()) {
            $(this).find('.remove-sub-menu-btn').prop('disabled', true);
        }
    });
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
function calculateRemainingTotalPrices() {
    $('#duplicate tr').each(function(index) {
        var rowId = $(this).find('td:eq(0) select').attr('id').match(/\d+/)[0];
        calculateTotalPrice(rowId);
    });
}

// Disable the Remove button for entries with IDs (from the database)
$('#duplicate tr').each(function(index) {
    if ($(this).find('input[name^="id"]').val()) {
        $(this).find('.remove-sub-menu-btn').prop('disabled', true);
    }
});
</script>
@endsection
