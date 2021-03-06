@extends('master')

@section('content')
<a href="{{ route('good_usage_rolling_door') }}">
   <input type="button" class="btn1" value="Back" />
</a>

<div class="container">
  <div id="Checkout" class="inline">
      <h1>Pilih Nomor Pesanan</h1>
      <form id="rolling-door-add" method="GET" action="{{ route('good_usage_rolling_door_add') }}" role="form">
          <div class="form-group">
              <label or="Nama">Nomor Pesanan</label>
              <select name="id" class="form-control" required>
                <option value""></option>
                <?php
                foreach ($option as $value) {
                    echo '<option value="'.$value['id'].'">'.$value['order_number'].'</option>';
                }
                ?>
              </select>
          </div>
          <button id="PayButton" class="btn btn-block btn-success submit-button" type="submit">
              <span class="align-middle">Cari</span>
          </button>
      </form>
  </div>
</div>

@endsection