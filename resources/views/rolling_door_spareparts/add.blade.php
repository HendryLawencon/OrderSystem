@extends('master')

@section('content')
<a href="{{ route('rolling_door_sparepart') }}">
   <input type="button" class="btn1" value="Kembali" />
</a>

<div class="container">
  <div id="Checkout" class="inline">
      <h1>Tambah Rolling Door Spareparts Item</h1>
      <form id="rolling_door-sparepart-add" method="POST" action="{{ route('rolling_door_sparepart_add_post') }}" role="form">
      {{ csrf_field() }}
          <div class="form-group">
              <label or="Nama">Nama Barang</label>
              <input name="name" class="form-control" type="text" maxlength="255" required></input>
          </div>
          <div class="form-group">
              <label for="satuan">Satuan</label>
              <select name="unit_id" class="form-control">
                <?php
                foreach ($option as $value) {
                    echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
                }
                ?>
              </select>
          </div>
          <div class="zip-code-group form-group">
              <label for="satuan">Harga</label>
              <div class="input-container">
                  <input name="price" class="form-control price" type="text" maxlength="10" required></input>
              </div>
          </div>
          <button id="PayButton" class="btn btn-block btn-success submit-button" type="submit">
              <span class="align-middle">Simpan</span>
          </button>
      </form>
  </div>
</div>

<script>
$(".price").keydown(function (e) {
  if(e.keyCode >= 35 && e.keyCode <= 40)
  {
    return;
  }
  if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
    return false;
  }
});
</script>
@endsection