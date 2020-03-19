<!-- --------------------------------------------------- -->
<div class="modal fade" id="addRiverModal_wh11" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Add New River</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text" style="width: 150px;" id="basic-addon1">River Name</span>
          </div>
          <input id="addNewRiver_wh11" type="text" class="form-control" placeholder="River Name" aria-label="Name" aria-describedby="basic-addon1">
        </div>
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text" style="width: 150px;" id="basic-addon1">Length (km)</span>
          </div>
          <input type="number" id="addNewRiver_wh11_length" type="text" class="form-control" placeholder="1234.00" aria-label="Name" aria-describedby="basic-addon1">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button id="addRiver_wh11" type="button" class="btn btn-primary">Add</button>
      </div>
    </div>
  </div>
</div>
<!-- ------------------------------------------------- -->
<H3 style="margin-bottom:40px"><strong><?php echo "[ WH1 ] " . $WeightKeysData['WH1']['name'] ?></strong></H3>

<H4 style="margin-bottom:20px">
  <a><a href="#" id=<?php echo "import-wh-11" ?>><i style="font-size:20px" class="fas fa-plus-circle"></i></a>
    <?php echo $WeightKeysData['WH11']['id'] ?> : <?php echo $WeightKeysData['WH11']['name'] ?> ( DO (mg/l) )
  </a>
</H4>
<!-- ------------------------------------------------- -->
<div class='table-responsive' style="margin-top:30px">
  <table id="editable_wh_11" class='table table-bordered table-hover'>
    <thead class='thead-dark'>
      <tr class="text-center">
        <th>#</th>
        <th>River Name</th>
        <th>Length (km)</th>
        <?php
        foreach ($YEAR_RANGE as $year) {
        ?>
          <th class='text-right'><?php echo $year ?></th>
        <?php }
        ?>
      </tr>
    </thead>
    <tbody>
      <?php
      $index = 1;
      foreach ($RiverDamList['WH1']['WH11'] as $key => $river) { ?>
        <tr class="text-center">
          <td><?php echo $index ?></td>
          <td style="white-space: nowrap;"><?php echo $river['name'] ?></td>
          <td><?php echo $river['length'] ?></td>
          <?php
          foreach ($river['table'] as $year => $value) {
          ?>
            <td class="text-right table-success">
              <a <?php echo "name='" . $river['id'] . "'" ?> <?php echo "id='" . $year . "'" ?> data-editable-wh11-river><?php echo $value ?></a>
            </td>
          <?php } ?>
        </tr>
      <?php $index++;
      } ?>
    </tbody>
  </table>
</div>

<?php include  __DIR__ . "./../../templates/utils/hr.html"; ?>
<!-- ------------------------------------------------- -->
<?php include  __DIR__ . "./../../templates/WH/weight_table/wh1_weight_table.php"; ?>
<!-- -------------------------------------------------------------------------------- -->
<!-- -------------------------------------------------------------------------------- -->
<script type="text/javascript">
  $('#import-wh-11').click(function() {
    $('#addRiverModal_wh11').modal('show');
  });
  $('#addRiver_wh11').click(function() {
    ///////////////////////////////////////
    var yearRange = <?php echo json_encode($YEAR_RANGE); ?>;
    var lastRiverId = $('#editable_wh_11 tr td a').last().attr('name')
    var name = $('#addNewRiver_wh11').val()
    var riverLength = $('#addNewRiver_wh11_length').val()
    var newKey = 'WH11_' + (parseInt(lastRiverId.toString().replace("WH11_", '')) + 1);
    var range = [];
    for (var i = 0; i < Object.keys(yearRange).length; i++) {
      range.push(0)
    }
    if (name !== "" && riverLength) { 
      var newData = ['WH1', 'WH11', '', newKey, "River", name, riverLength ? riverLength : 0, 'DO (mg/l)', ...range];
      $.ajax({
        url: "actions/act_append.php",
        type: 'post',
        data: {
          'id': 'addNewRiver_wh11',
          'data': newData,
          'sheet_id': <?php echo json_encode($RIVER_DAM_LIST_SHEET); ?>,
        },
        success: function(response) {
          $('#addRiverModal_wh11').modal('hide');
          $('#loading').show()
          $('#editable_wh_11').load(location.href + " #editable_wh_11", function() {
            $("#wh-WH1-score-table").load(location.href + " #wh-WH1-score-table");
            $('#loading').hide()
            $('#addNewRiver_wh11').val('');
            $('#addNewRiver_wh11_length').val('');
          });
        }
      });
    } else {
      alert("Cannot be empty")
    }
  })
  ////////////////////////////////////////////////////////////
  $('body').on('click', '[data-editable-wh11-river]', function(e) {
    e.preventDefault();
    var $el = $(this);
    var $input = $('<input type="number" id="' + $el.attr("id") + '" name="' + $el.attr("name") + '" type="text" style="min-width:100px;" class="form-control">').val($el.text());
    $el.replaceWith($input);
    var save = function() {
      var $a = $('<a id="' + $el.attr("id") + '" name="' + $el.attr("name") + '" data-editable-wh11-river />').text($input.val());
      $input.replaceWith($a);
    };
    $input.one('blur', save).focus();
    ////////////////////////////////////////////////////////////////////
    function callAjax(data) {
      return $.ajax({
        url: 'actions/act_edit_cell.php',
        type: 'post',
        data: data,
        success: function(response) {
          $("#wh-WH1-score-table").load(location.href + " #wh-WH1-score-table");
        },
      })
    }
    ////////////////////////////////////////////////////////////////////
    $input.keydown(function(e) {
      if (e.keyCode == 13 | e.keyCode == 9) {
        callAjax({
          id: $el.attr("name"),
          sheet_id: <?php echo json_encode($RIVER_DAM_LIST_SHEET); ?>,
          year: $el.attr("id"),
          val: $input.val(),
        });
        $input.blur();
      };
    })
    $input.blur(function() {
      callAjax({
        id: $el.attr("name"),
        sheet_id: <?php echo json_encode($RIVER_DAM_LIST_SHEET); ?>,
        year: $el.attr("id"),
        val: $input.val(),
      });
    })
  });
</script>