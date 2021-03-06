@extends('master')

@section('content')
<a href="{{ route('rolling_door_order_add') }}">
   <input type="button" class="btn1" value="+ Pesanan" />
</a>

<div class="container">
   @include('flash::message')
  <div id="Checkout" class="inline">

      <h1>Pesanan Rolling Door</h1>
      <table class="table table-bordered" id="rolling-door-order-table">
        <thead>
            <tr>
                <th>Nomor Order</th>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Grand Total</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
  </div>
</div>    

<script>
$(function() {
    $('#rolling-door-order-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
                  url: '{{ route('datatable_rolling_door_order') }}',
              },
        columns: [
            { data: 'order_number', name: 'order_number' },
            { data: 'name', name: 'name' },
            { data: 'order_date', name: 'order_date' },
            { data: 'grand_total', name: 'grand_total' },
            {
                field: 'operate',
                align: 'center',
                mRender: function (data, type, full) {
                  <?php
                  if(strtolower(Auth::user()->role->name) == 'super admin')
                  {
                  ?>

                  return '<a class="btn2" href="{{ route('rolling_door_order_view') }}?id=' + full.id + '">LIHAT</a>\
                          <a class="btn2" href="{{ route('rolling_door_order_edit') }}?id=' + full.id + '">EDIT</a>\
                          <a class="btn2" href="{{ route('rolling_door_order_delete') }}?id=' + full.id + '">HAPUS</a>\
                          <a class="btn2" target="_blank" href="{{ route('print_rolling_door_order') }}?id=' + full.id + '">PDF</a>'
                          }}
                  <?php
                  }
                  else
                  {
                    ?>
                  return '<a class="btn2" href="{{ route('rolling_door_order_view') }}?id=' + full.id + '">LIHAT</a>\
                        <a class="btn2" target="_blank" href="{{ route('print_rolling_door_order') }}?id=' + full.id + '">PDF</a>'
                        }}

                  <?php
                  }
                  ?>
        ],
        initComplete: function () {
            // drawCallBack();
        }
    });
});
</script>
@endsection