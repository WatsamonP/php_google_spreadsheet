<!-- --------------------------------------------------- -->
<div class="modal fade" id="addReservoirModal_wa62" tabindex="-1" role="dialog">
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
          <input id="addNewReservoir_wa62" type="text" class="form-control" placeholder="Name" aria-label="Name" aria-describedby="basic-addon1">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button id="addReservoir_wa62" type="button" class="btn btn-primary">Add</button>
      </div>
    </div>
  </div>
</div>
<!-- ------------------------------------------------- -->
<div class='table-responsive' style="margin-top:50px;">
  <H4 style="margin-bottom:20px">
    <a><a href="#" id="import-wa-62"><i style="font-size:20px" class="fas fa-plus-circle"></i></a>
      <?php echo $WeightKeysData['WA62']['id'] ?> : <?php echo $WeightKeysData['WA62']['name'] ?>
    </a>
  </H4>
  <table id="editable_wa_62" class='table table-bordered table-hover'>
    <thead class='thead-dark'>
      <tr class="text-center">
        <th style="width:40px;"></th>
        <th style="width:100px;">No of Reservoirs</th>
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
          <td><a id="DeleteButton" thisId="<?php echo $item['id']; ?>" name="<?php echo $item['key']; ?>" class="text-secondary" href="#" class="text-right"><i class="fas fa-trash"></i></a></td>
          <td><?php echo $index; ?></td>
          <td><?php echo $item['key']; ?></td>
          <td style="cursor: pointer" class="table-success">
            <a <?php echo "id='" . $item['id'] . "'" ?> data-editable-wa62-reservoirs><?php echo $item['value'] ?></a>
          </td>
        </tr>
      <?php $index++;
      } ?>
    </tbody>
  </table>
</div>
<!-- -------------------------------------------------------------------------------- -->
<script type="text/javascript">
  $("#editable_wa_62").on("click", "#DeleteButton", function() {
    $el = $(this);
    var id = $el.attr('thisId');
    var name = $el.attr('name');
    if (confirm("Do you want to delete " + name + " ?")) {
      if (id !== null) {
        $.ajax({
          url: 'actions/act_delete_rows.php',
          type: 'post',
          data: {
            id_column: "E",
            id: id,
            sheet_id: <?php echo json_encode($SPECIFIC_INPUT_SHEET); ?>,
            gid: <?php echo json_encode($SPECIFIC_INPUT_SHEET_GID); ?>,
          },
          success: function(response) {
            $el.closest("tr").remove();
            $('#loading').show()
            $('#editable_wa_62').load(location.href + " #editable_wa_62", function() {
              $("#wa-WA6-score-table").load(location.href + " #wa-WA6-score-table");
              $('#loading').hide()
            });
          }
        });
      }
    }
    return false;
  });
  ////////////////////////////////////////
  $('#import-wa-62').click(function() {
    $('#addReservoirModal_wa62').modal('show');
  });
  $('#addReservoir_wa62').click(function() {
    ///////////////////////////////////////
    var name = $('#addNewReservoir_wa62').val()
    var newKey = 'WA62_' + name.toString().toLowerCase().replace(" ", '_');

    if (name !== "") {

      var newData = ['WA6', 'WA62', 'reservoirs', name, newKey, 0];
      $.ajax({
        url: "actions/act_append.php",
        type: 'post',
        data: {
          id: 'addNewReservoir_wa62',
          data: newData,
          sheet_id: <?php echo json_encode($SPECIFIC_INPUT_SHEET); ?>,
        },
        success: function(response) {
          $('#addReservoirModal_wa62').modal('hide');
          $('#loading').show()
          $('#editable_wa_62').load(location.href + " #editable_wa_62", function() {
            $("#wa-WA6-score-table").load(location.href + " #wa-WA6-score-table");
            $('#addNewReservoir_wa62').val('');
            $('#loading').hide()
          });
        }
      });
    } else {
      alert("Cannot be empty")
    }
  })
  ////////////////////////////////////////////////////////////
  $('body').on('click', '[data-editable-wa62-reservoirs]', function(e) {
    e.preventDefault();
    var $el = $(this);
    var $input = $('<input type="number" id="' + $el.attr("id") + '" name="' + $el.attr("name") + '" type="text" style="min-width:100px;" class="form-control">').val($el.text());
    $el.replaceWith($input);
    var save = function() {
      var $a = $('<a id="' + $el.attr("id") + '" name="' + $el.attr("name") + '" data-editable-wa62-reservoirs />').text($input.val());
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
          $('#loading').show()
          $("#wa-WA6-score-table").load(location.href + " #wa-WA6-score-table", function() {
            $('#loading').hide()
          });
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