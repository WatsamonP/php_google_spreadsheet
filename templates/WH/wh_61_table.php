<!-- --------------------------------------------------- -->
<div class="modal fade" id="addRiverModal_wh61" tabindex="-1" role="dialog">
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
          <input id="addNewRiver_wh61" type="text" class="form-control" placeholder="River Name" aria-label="Name" aria-describedby="basic-addon1">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button id="addRiver_wh61" type="button" class="btn btn-primary">Add</button>
      </div>
    </div>
  </div>
</div>
<!-- ------------------------------------------------- -->
<H3 style="margin-bottom:40px"><strong><?php echo "[ WH6 ] " . $WeightKeysData['WH6']['name'] ?></strong></H3>

<H4 style="margin-bottom:20px">
  <a><a href="#" id=<?php echo "import-wh-61" ?>><i style="font-size:20px" class="fas fa-plus-circle"></i></a>
    <?php echo $WeightKeysData['WH61']['id'] ?> : <?php echo $WeightKeysData['WH61']['name'] ?>
  </a>
</H4>
<!-- ------------------------------------------------- -->
<div class='table-responsive' style="margin-top:30px">
  <table id="editable_wh_61" class='table table-bordered table-hover'>
    <thead class='thead-dark'>
      <tr class="text-center">
        <th style="width:40px;"></th>
        <th>#</th>
        <th>River Name</th>
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
      foreach ($RiverDamList['WH6']['WH61'] as $key => $river) { ?>
        <tr class="text-center">
          <td><a id="DeleteButton" thisId="<?php echo $river['id']; ?>" name="<?php echo $river['name']; ?>" class="text-secondary" href="#" class="text-right"><i class="fas fa-trash"></i></a></td>
          <td><?php echo $index ?></td>
          <td style="white-space: nowrap;"><?php echo $river['name'] ?></td>
          <?php
          foreach ($river['table'] as $year => $value) {
          ?>
            <td style="cursor: pointer" class="text-right table-success">
              <a <?php echo "name='" . $river['id'] . "'" ?> <?php echo "id='" . $year . "'" ?> data-editable-wh61-river><?php echo $value ?></a>
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
<?php include  __DIR__ . "./../../templates/WH/weight_table/wh6_weight_table.php"; ?>
<!-- -------------------------------------------------------------------------------- -->
<!-- -------------------------------------------------------------------------------- -->
<script type="text/javascript">
  $("#editable_wh_61").on("click", "#DeleteButton", function() {
    $el = $(this);
    var id = $el.attr('thisId');
    var name = $el.attr('name');
    if (confirm("Do you want to delete " + name + " ?")) {
      if (id !== null) {
        $.ajax({
          url: 'actions/act_delete_rows.php',
          type: 'post',
          data: {
            id_column: "D",
            id: id,
            sheet_id: <?php echo json_encode($RIVER_DAM_LIST_SHEET); ?>,
            gid: <?php echo json_encode($RIVER_DAM_LIST_GID); ?>,
          },
          success: function(response) {
            $el.closest("tr").remove();
            $('#loading').show()
            $('#editable_wh_61').load(location.href + " #editable_wh_61", function() {
              $("#wh-WH6-score-table").load(location.href + " #wh-WH6-score-table");
              $('#loading').hide()
            });
          }
        });
      }
    }
    return false;
  });
  ///////////////////////////////////////
  $('#import-wh-61').click(function() {
    $('#addRiverModal_wh61').modal('show');
  });
  $('#addRiver_wh61').click(function() {
    ///////////////////////////////////////
    var yearRange = <?php echo json_encode($YEAR_RANGE); ?>;
    var lastRiverId = $('#editable_wh_61 tr td a').last().attr('name')
    var name = $('#addNewRiver_wh61').val()
    var newKey = 'WH61_' + (parseInt(lastRiverId.toString().replace("WH61_", '')) + 1);
    var range = [];
    for (var i = 0; i < Object.keys(yearRange).length; i++) {
      range.push(0)
    }
    if (name !== "") {
      var newData = ['WH6', 'WH61', '', newKey, "River", name, '', '', ...range];
      $.ajax({
        url: "actions/act_append.php",
        type: 'post',
        data: {
          'id': 'addNewRiver_wh61',
          'data': newData,
          'sheet_id': <?php echo json_encode($RIVER_DAM_LIST_SHEET); ?>,
        },
        success: function(response) {
          $('#addRiverModal_wh61').modal('hide');
          $('#loading').show()
          $('#editable_wh_61').load(location.href + " #editable_wh_61", function() {
            $("#wh-WH6-score-table").load(location.href + " #wh-WH6-score-table");
            $('#loading').hide()
            $('#addNewRiver_wh61').val('');
          });
        }
      });
    } else {
      alert("Cannot be empty")
    }
  })
  ////////////////////////////////////////////////////////////
  $('body').on('click', '[data-editable-wh61-river]', function(e) {
    e.preventDefault();
    var $el = $(this);
    var $input = $('<input type="number" id="' + $el.attr("id") + '" name="' + $el.attr("name") + '" type="text" style="min-width:100px;" class="form-control">').val($el.text());
    $el.replaceWith($input);
    var save = function() {
      var $a = $('<a id="' + $el.attr("id") + '" name="' + $el.attr("name") + '" data-editable-wh61-river />').text($input.val());
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
          $("#wh-WH6-score-table").load(location.href + " #wh-WH6-score-table");
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