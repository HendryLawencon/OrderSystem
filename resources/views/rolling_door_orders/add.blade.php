@extends('master')

@section('content')
<a href="{{ route('rolling_door_order') }}">
   <input type="button" class="btn1" value="Kembali" />
</a>

<div class="container">
  <div id="Checkout" class="inline">
      <h1>Tambah Rolling Door Order</h1>
      <form id="rolling-door-order-add" method="POST" action="{{ route('rolling_door_order_add_post') }}" role="form" >
      {{ csrf_field() }}
          <div class="form-group">
              <label or="Date">Tanggal</label>
              <input name="date" type="text" class="form-control" id="datetimepicker2" maxlength="10" required></input>
          </div>
          <div class="form-group">
              <label or="Nama">Nama</label>
              <input name="name" class="form-control" type="text" maxlength="255" required></input>
          </div>
          <div class="form-group">
              <label or="Address">Alamat</label>
              <input name="address" class="form-control" type="text" maxlength="255" required></input>
          </div>
          <div class="form-group">
              <label or="Phone">Nomor Telepon</label>
              <input name="phone" class="form-control phone-number" type="text" maxlength="255" required></input>
          </div>

          <table id="mytable" class="table table-striped">
              <thead>
                  <th>Qty</th>
                  <th>Nama Barang</th>
                  <th>Harga/Satuan</th>
                  <th>Size</th>
                  <th>Satuan</th>
              </thead>
              <tbody>
                  <?php
                  for($i=1;$i<=12;$i++)
                  {
                    ?>
                    <tr data-id="<?php echo $i ?>">
                      <td>
                          <input type="text" id="fee-3" class="form-control" name="order[<?php echo $i ?>][qty]" <?php echo $i==1? 'required':''?>>
                      </td>
                      <td>
                          <select name="order[<?php echo $i ?>][rolling_door_id]" class="form-control ItemName" onclick="checkprice(this)" <?php echo $i==1? 'required':''?>>
                              <option value="" selected="selected"></option>
                              <?php
                                foreach ($option as $value) {
                                    echo '<option value="'.$value['id'].'">'.$value['name'].'</option>';
                                }
                              ?>
                          </select>
                      </td>
                      <td>
                          <input type="number" id="price-<?php echo $i ?>" class="form-control no-spin" name="order[<?php echo $i ?>][price]" min="0" <?php echo $i==1? 'required':''?>>
                      </td>
                      <td>
                          <input type="text" id="fee-4" class="form-control" name="order[<?php echo $i ?>][size]" <?php echo $i==1? 'required':''?>>
                      </td>
                      <td>
                          <input type="text" id="unit-<?php echo $i ?>" class="form-control" readonly>
                      </td>
                  </tr>
                  <?php
                    }
                  ?>
              </tbody>
          </table>
        
          
          <button id="PayButton" class="btn btn-block btn-success submit-button" type="submit">
              <span class="align-middle">Simpan</span>
          </button>
      </form>
  </div>
</div>


<script>

function checkform()
{
  var data = document.getElementsByClassName("ItemName");
  var array_check = new Array();

  for (var i = 0, len = data.length; i < len; i++) 
  {
    if(data[i].value == '')
    {
      continue;
    }
    if($.inArray(data[i].value, array_check) != -1)
    {
      alert('Data ganda ditemukan. Periksa kembali sebelum disimpan');
      return false;
    }
    else
    {
      array_check.push(data[i].value);
    }
    
  }
  return true;
}

function checkprice(row)
{
    var rownumber = row.parentNode.parentNode.getAttribute('data-id');
    var pricecomponent = "price-" + rownumber;
    var unitcomponent = "unit-" + rownumber;
    $.ajax({
        url: '{{ route("rolling_door_price") }}?id='+row.value,
        method: 'GET',
        dataType: 'JSON',
        // context: document.body,
        success: function(data){
          document.getElementById(pricecomponent).value=data.value;
          document.getElementById(unitcomponent).value=data.units;
          document.getElementById(pricecomponent).setAttribute('min', data.value);
        }
    });
}

$('#datetimepicker2').datepicker({ 
    format: 'dd-mm-yyyy',
    autoclose: true
});


$("#datetimepicker2").keydown(function (e) {
    // Allow: backspace, delete, tab, escape, enter and .
    // if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1) 
    // {
    //    // let it happen, don't do anything
    //    return;
    // }
    // else
    // {
    //   e.preventDefault();
    // }
    e.preventDefault();
});

$(".phone-number").keydown(function (e) {
  if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
    return false;
  }
});
</script>

@endsection
