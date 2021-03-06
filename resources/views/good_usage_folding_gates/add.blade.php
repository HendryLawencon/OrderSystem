@extends('master')

@section('content')
<a href="{{ route('good_usage_folding_gate') }}">
   <input type="button" class="btn1" value="Back" />
</a>

<div class="container">
  @include('flash::message')
  <div id="Checkout" class="inline">
    <h1>Tambah Penggunaan Barang Folding Gate</h1>
      <form id="good-usage-folding-gate-add" method="POST" action="{{ route('good_usage_folding_gate_add_post') }}" onsubmit="return checkform();" role="form">
      {{ csrf_field() }}
      <table id="mytable" class="table table-striped">
          <thead>
              <th>Qty</th>
              <th>Nama Barang</th>
              <th>Size</th>
              <th>Satuan</th>
          </thead>
          <tbody>
              <?php
              foreach($content as $key => $child_value)
              {
                ?>
                <tr>
                  <td>
                      <input type="text" class="form-control" value="<?php echo $child_value['qty'] ?>" readonly>
                  </td>
                  <td>
                      <input type="text" class="form-control" value="<?php echo $child_value['ItemName'] ?>" readonly>
                  </td>
                  <td>
                      <input type="text" class="form-control" value="<?php echo $child_value['size'] ?>" readonly>
                  </td>
                  <td>
                      <input type="text" class="form-control" value="<?php echo $child_value['UnitName'] ?>" readonly>
                  </td>
              </tr>
              <?php
                }
              ?>
          </tbody>
      </table>

     @include('good_usage_folding_gates.template')

      
      <input type="hidden" name='folding_gate_order_id' value={{$id}}></input>
      <section class="tabs-section">
        <div role="tabpanel" class="tab-pane" id="realization">
            <a href="#" class="btn btn-inline btn-primary btn-sm btn-add" data-tmpl="#form-add-request-tmpl" data-style="expand-left">
              <i class="mdi mdi-plus-circle mdi-20px"></i>&nbsp;Tambah Barang
            </a>

            <br/>
              
              <table id="tableRequest" class="table table-striped">
                  <thead> 
                    <div class="row m-t-lg">
                      <div class="col-md-3"><label class="form-label">Kode Barang</label></div>
                      <div class="col-md-3"><label class="form-label">Kuota</label></div>
                      <div class="col-md-3"><label class="form-label">Panjang (meter)</label></div>
                      <div class="col-md-3 action"><label class="form-label">Action</label></div>
                    </div>
                  </thead>
                  <tbody>
                      <div class="detail">
                        <div class="row m-t-lg rows form_tmpl" data-id="0" id="tmpl-0">

                        <div class="col-md-3">
                          <div class="form-group">
                            <div class="form-control-wrapper">
                              <select name="detail[0][item_code]" class="form-control item_code" id="order-0" required">
                                  <option value="" selected="selected"></option>
                                  <?php
                                    foreach ($option as $value) {
                                        echo '<option value="'.$value['item_code'].'">'.$value['item_code'].'</option>';
                                    }
                                  ?>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <div class="form-control-wrapper">
                              <input class="form-control amount_real-0"
                                     id="quota-0"
                                     type="text" readonly>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-3">
                          <div class="form-group">
                            <div class="form-control-wrapper">
                              <input class="form-control amount_real-0"
                                     name="detail[0][length]"
                                     id="length-0"
                                     type="number" min=0 max=5 step="0.001" required>
                            </div>
                          </div>
                        </div>
                      </div>
                      </div>
                  </tbody>
                  
              </table>
        </div>
      </section>

          <button id="PayButton" class="btn btn-block btn-success submit-button" type="submit">
              <span class="align-middle">Simpan</span>
          </button>
      </form>
  </div>
</div>



<script>

function checkform()
{
  var data = document.getElementsByClassName("item_code");
  var array_check = new Array();

  for (var i = 0, len = data.length; i < len; i++) 
  {
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



$('.btn-add').on('click', function(event){
    event.preventDefault();
    fn._addEvent($(this), $(this).data('tmpl'));
});

var fn = {
    _addEvent : function($elem, tmpl, callback){

        if (!tmpl) {
            return;
        }

        var self = this;
        var $targ = $elem.closest('section').find('.detail');
        var index = $targ.find('.form_tmpl:last-child');
        var check = $targ.find('tr').length;

        if($(index).length == 0) {
          index = 0;
        } else {
          index = $(index).data('id') + 1;
        }

        var templateDom = $(tmpl).html();
        
        var template = Handlebars.compile(templateDom);
        var context = {
            index: index
        };
        
        var html = template(context);

        $(html).appendTo($targ);

        $('#action_delete-'+index).on('click', function (event) {

            /*console.log('delete ya');*/
            self._deleteEvent($(this), index);

        });

        $('#order-'+index).on('change', function(event){
            event.preventDefault();
            $.ajax({
                url: '{{ route("good_usage_get_quota_folding_gate") }}?id='+$(this).val(),
                method: 'GET',
                // dataType: 'JSON',
                // context: document.body,
                success: function(data){
                    $("#quota-"+index).val(data);
                    $("#length-"+index).val(0);
                    $("#length-"+index).attr("max", data);
                }
            });
        });
    },

    _deleteEvent: function ($elem, index) {
        $elem.parent().parent().parent().parent().remove();
    },
}


$('#order-0').on('change', function(event){
    event.preventDefault();
    $.ajax({
        url: '{{ route("good_usage_get_quota_folding_gate") }}?id='+$(this).val(),
        method: 'GET',
        // dataType: 'JSON',
        // context: document.body,
        success: function(data){
            $("#quota-0").val(data);
            $("#length-0").val(0);
            $("#length-0").attr("max", data);
        }
    });
});

</script>
@endsection
