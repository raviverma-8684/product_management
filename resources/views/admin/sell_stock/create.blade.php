@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('cruds.sell_stock.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.sell_stock.store") }}" enctype="multipart/form-data">
            @csrf

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
                    <tr>
                        <td></td>
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
                                <input class="form-control" type="number" name="quantity[]" id="quantity0" required>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input class="form-control" type="number" name="selling_price[]" id="selling_price0" required>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input class="form-control" type="number" name="ordered_quantity[]" id="ordered_quantity0" required>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <select class="form-control select2" name="discount_type[]" id="discount_type0">
                                    <option value="">Please Select</option>
                                    <option value="0">Percentage</option>
                                    <option value="1">Flat</option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="form-group">
                                <input class="form-control" type="number" name="discount[]" id="discount0" required>
                            </div>
                        </td>
                        
                        <td>
                            <div class="form-group">
                                <input class="form-control" type="number" name="total_selling_price[]" id="total_selling_price0" required>
                            </div>
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
        <td></td>
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
            <button type="button" class="btn btn-danger remove-sub-menu-btn mt-2">Remove</button>
        </td>
    </tr>
    `;

    // Append the new row to the table
    $('#duplicate').append(subMenuTemplate);
});

$(document).on('click', '.remove-sub-menu-btn', function() {
    $(this).closest('tr').remove();
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
    $('#total_selling_price' + rowId).val(totalSellingPrice.toFixed(0));
}

// Add an event listener for relevant input fields in each row
$(document).on('change', "[id^='discount_type'], [id^='discount'], [id^='selling_price'], [id^='ordered_quantity']", function() {
  
    var rowId = $(this).attr('id').match(/\d+/)[0];
    calculateTotalSellingPrice(rowId);
});

// Initial calculation when the page loads
$(document).ready(function() {
    calculateTotalSellingPrice(0); // Assuming there's one row initially
});

</script>
@endsection
