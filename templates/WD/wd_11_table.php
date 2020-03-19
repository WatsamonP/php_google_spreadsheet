<!-- --------------------------------------------------- -->
<div class="modal fade" id="addReservoirModal_wd11" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Add New Reservoirs</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">Reservoirs</span>
          </div>
          <input id="addNewReservoir_wd11" type="text" class="form-control" placeholder="Name" aria-label="Name" aria-describedby="basic-addon1">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button id="addReservoir_wd11" type="button" class="btn btn-primary">Add</button>
      </div>
    </div>
  </div>
</div>
<!-- ------------------------------------------------- -->
<H3 style="margin-bottom:40px"><strong><?php echo "[ WD1 ] " . $WeightKeysData['WD1']['name'] ?></strong></H3>

<H4 style="margin-bottom:20px">
  <a><a href="#" id=<?php echo "import-wd-11" ?>><i style="font-size:20px" class="fas fa-plus-circle"></i></a>
    <?php echo $WeightKeysData['WD11']['id'] ?> : <?php echo $WeightKeysData['WD11']['name'] ?>
  </a>
</H4>
<!-- ------------------------------------------------- -->
<div id="editable_wd_11" class='table-responsive' style="margin-top:30px">
  <table class='table table-bordered table-hover'>
    <thead class='thead-dark'>
      <tr class="text-center">
        <th style="width:150px;">No of Reservoirs</th>
        <th>Name</th>
        <th>Capacity</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $index = 1;
      foreach ($SpecificInput['WA6']['WA62']['reservoirs'] as $key => $item) {
      ?>
        <tr class="text-center">
          <td><?php echo $index ?></td>
          <td><?php echo $item['key'] ?></td>
          <td class="table-success">
            <a <?php echo "id='" . $item['id'] . "'" ?> data-editable-wd11-reservoirs><?php echo $item['value'] ?></a>
          </td>
        </tr>
      <?php
        $index++;
      }
      ?>
    </tbody>
  </table>
</div>
<?php include  __DIR__ . "./../../templates/utils/hr.html"; ?>
<!-- ------------------------------------------------- -->
<?php include  __DIR__ . "./../../templates/WD/weight_table/wd1_weight_table.php"; ?>
<!-- -------------------------------------------------------------------------------- -->
<!-- -------------------------------------------------------------------------------- -->
<script type="text/javascript">
  $('#import-wd-11').click(function() {
    $('#addReservoirModal_wd11').modal('show');
  });
  $('#addReservoir_wd11').click(function() {
    ///////////////////////////////////////
    var name = $('#addNewReservoir_wd11').val()
    var newKey = 'WA62_' + name.toString().toLowerCase().replace(" ", '_'); // ?????? not sure

    if (name !== "") {
      var newData = ['WA6', 'WA62', 'reservoirs', name, newKey, 0];
      $.ajax({
        url: "actions/act_append.php",
        type: 'post',
        data: {
          id: 'addNewReservoir_wd11',
          data: newData,
          sheet_id: <?php echo json_encode($SPECIFIC_INPUT_SHEET); ?>,
        },
        success: function(response) {
          $('#addReservoirModal_wd11').modal('hide');
          $('#loading').show()
          $('#editable_wd_11').load(location.href + " #editable_wd_11", function() {
            $("#wd-WD1-score-table").load(location.href + " #wd-WD1-score-table");
            $('#addNewReservoir_wd11').val('');
            $('#loading').hide()
          });
        }
      });
    } else {
      alert("Cannot be empty")
    }
  })
  ////////////////////////////////////////////////////////////
  $('body').on('click', '[data-editable-wd11-reservoirs]', function(e) {
    e.preventDefault();
    var $el = $(this);
    var $input = $('<input type="number" id="' + $el.attr("id") + '" type="text" style="min-width:100px;" class="form-control">').val($el.text());
    $el.replaceWith($input);
    var save = function() {
      var $a = $('<a id="' + $el.attr("id") + '" data-editable-wd11-reservoirs />').text($input.val());
      $input.replaceWith($a);
    };
    $input.one('blur', save).focus();
    ////////////////////////////////////////////////////////////////////
    function callAjax(data) {
      return $.ajax({
        url: 'actions/act_edit_specific.php',
        type: 'post',
        data: data,
        success: function(response) {
          $("#wd-WD1-score-table").load(location.href + " #wd-WD1-score-table");
        },
      })
    }
    ////////////////////////////////////////////////////////////////////
    $input.keydown(function(e) {
      if (e.keyCode == 13 | e.keyCode == 9) {
        callAjax({
          id: $el.attr("id"),
          sheet_id: <?php echo json_encode($SPECIFIC_INPUT_SHEET); ?>,
          val: $input.val(),
        });
        $input.blur();
      };
    })
    $input.blur(function() {
      callAjax({
        id: $el.attr("id"),
        sheet_id: <?php echo json_encode($SPECIFIC_INPUT_SHEET); ?>,
        val: $input.val(),
      });
    })
  });
</script>