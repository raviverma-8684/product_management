@extends('layouts.admin')
@section('content')
@can('product_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.po.create') }}">
            {{ trans('global.add') }} {{ trans('cruds.po.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
       {{ trans('cruds.po.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Product">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.po.fields.sn') }}
                        </th>
                        <th>
                        {{ trans('cruds.po.title_singular') }} {{ trans('cruds.po.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.po.fields.total_quantity') }}
                        </th>
                        <th>
                            {{ trans('cruds.po.fields.total_price') }}
                        </th>
                        <th>
                            {{ trans('cruds.po.fields.status') }}
                        </th>
                        
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                @foreach($po as $key => $po)
                
                        <tr data-entry-id="{{ $po['id'] ?? '' }}">
                            <td>

                            </td>
                            <td>
                                {{$loop -> index+1 }}
                            </td>
                            <td>
                            {{ $po['id'] ?? '' }}
                            </td>
                            <td>
                                {{ $po['total_quantity'] ?? '' }}
                            </td>
                            <td>
                                {{ $po['total_price'] ?? '' }}
                            </td>
                            <td>
                                <?php
                                    if($po['status'] === 0){
                                        echo "Pending";
                                    }
                                    elseif($po['status'] === 1){
                                        echo "Partially Completed";
                                    }
                                    elseif($po['status'] === 2){
                                        echo "completed";
                                    }
                                    elseif($po['status'] === 3){
                                        echo "Closed";
                                    }
                                    elseif($po['status'] === 4){
                                        echo "Cancelled";
                                    }

                                    
                                    ?>
                            </td>
                            <td>
                            @can('po_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.po.show',$po['id'] ?? '' ) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan
                                @if($po['status'] == 0 || $po['status'] == 1 ) 
                                @can('po_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.po.edit', $po['id'] ?? '' ) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan
                                @endif
                                @if($po['status'] === 1 )
                                @can('po_close')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.po.close', $po['id'] ?? '' ) }}">
                                        {{ trans('global.close') }}
                                    </a>
                                @endcan
                                @endif
                                @if($po['status'] === 0 )
                                @can('po_cancel')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.po.cancel', $po['id'] ?? '' ) }}">
                                        {{ trans('global.cancel') }}
                                    </a>
                                @endcan
                                @endif

                                @can('po_delete')
                                    <form action="{{ route('admin.po.destroy', $po['id'] ?? '' ) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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