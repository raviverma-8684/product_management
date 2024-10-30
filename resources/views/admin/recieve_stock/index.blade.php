@extends('layouts.admin')
@section('content')
@can('recieve_stock_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.recieve_stock.create') }}">
              {{ trans('cruds.recieve_stock.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
    {{ trans('cruds.recieve_stock.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Product">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.recieve_stock.fields.sn') }}
                        </th>
                        <th>
                            {{ trans('cruds.recieve_stock.fields.po_id') }}
                        </th>
                        <th>
                            {{ trans('cruds.recieve_stock.fields.total_quantity') }}
                        </th>
                        <th>
                            {{ trans('cruds.recieve_stock.fields.recieved_quantity') }}
                        </th>
                       
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                @foreach($rs as $rs)
                        <tr>
                            <td></td>
                            <td>{{$loop -> index+1 }}</td>
                            <td>{{ $rs->po_id }}</td>
                            <td>{{ $rs->PO->total_quantity }}</td>
                            <td>{{ $rs->total_received }}</td>
                           
                          
                            <td>
                               @can('recieve_stock_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.recieve_stock.show', $rs->po_id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                <!-- @can('recieve_stock_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.recieve_stock.edit', $rs->po_id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan -->

                                @can('recieve_stock_delete')
                                    <form action="{{ route('admin.recieve_stock.destroy', $rs->po_id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@endsection