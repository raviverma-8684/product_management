@extends('layouts.admin')
@section('content')
@can('sell_stock_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.sell_stock.create') }}">
             {{ trans('cruds.sell_stock.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
       {{ trans('cruds.sell_stock.title_singular') }}{{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Product">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.sell_stock.fields.sn') }}
                        </th>
                        <th>
                            {{ trans('cruds.sell_stock.fields.total_ordered_quantity') }}
                        </th>
                        <th>
                            {{ trans('cruds.sell_stock.fields.total_selling_price') }}
                        </th>
                        
                        
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                @foreach($sell_stock as $key => $sell_stock)
                        <tr data-entry-id="{{ $sell_stock['id'] ?? '' }}">
                            <td>

                            </td>
                            <td>
                                {{$loop -> index+1 }}
                            </td>
                            <td>
                                {{ $sell_stock->total_ordered_quantity }}
                            </td>
                            <td>
                                {{ $sell_stock->total_ordered_price }}
                            </td>
                            
                            <td>
                            @can('sell_stock_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.sell_stock.show',$sell_stock['id'] ?? '' ) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('sell_stock_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.sell_stock.edit', $sell_stock['id'] ?? '' ) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('sell_stock_delete')
                                    <form action="{{ route('admin.sell_stock.destroy', $sell_stock['id'] ?? '' ) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan
                            </td>
                           

                        </tr>
                        @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('product_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
   
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-Product:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('product_employee_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.employee.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
     orderCellsTop: true,
     order: [[ 1, 'desc' ]],
     pageLength: 10,
   });
  let table = $('.datatable-ProductCategory:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection