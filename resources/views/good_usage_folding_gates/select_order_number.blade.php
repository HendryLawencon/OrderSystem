@extends('master')

@section('content')
<a href="{{ route('good_usage_folding_gate') }}">
   <input type="button" class="btn1" value="Kembali" />
</a>

<div class="container">
  <div id="Checkout" class="inline">
      <h1>Pilih Nomor Pesanan</h1>
      <form id="folding-gate-add" method="GET" action="{{ route('good_usage_folding_gate_add') }}" role="form">
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