<!-- --------------------------------------------------- -->
<div class="modal fade" id="addDamModal_wp31" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-plus-circle"></i> Add New Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">Name</span>
          </div>
          <input id="addNewDam_wp31" type="text" class="form-control" placeholder="Name" aria-label="Name" aria-describedby="basic-addon1">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button id="addDam_wp31" type="button" class="btn btn-primary">Add</button>
      </div>
    </div>
  </div>
</div>
<!-- ------------------------------------------------- -->
<H3 style="margin-bottom:40px"><strong><?php echo "[ WP3 ] " . $WeightKeysData['WP3']['name'] ?></strong></H3>
<!-- ------------------------------------------------- -->
<H4 style="margin-bottom:20px"><?php echo $WeightKeysData['WP31']['id'] ?> : <?php echo $WeightKeysData['WP31']['name'] ?></H4>
<!-- ------------------------------------------------- -->
<div class='table-responsive' style="margin-top:30px">
  <H5>
    <a>
      <a href="#" id=<?php echo "import-wp-31-a" ?>><i style="font-size:20px" class="fas fa-plus-circle"></i></a> Electricity Production (<?php echo toSup("GWh") ?>)
    </a>
  </H5>
  <table id="editable_wp_31-a" class='table table-bordered table-hover'>
    <thead class='thead-dark'>
      <tr class="text-center">
        <th scope="col">#</th>
        <th scope="col">Name</th>
        <?php
        foreach ($YEAR_RANGE as $year) { ?>
          <th class='text-right'><?php echo $year ?></th>
        <?php }
        ?>
      </tr>
    </thead>
    <tbody>
      <?php
      $index = 1;
      foreach ($RiverDamList['WP3']['WP31_A'] as $key => $dam) {
      ?>
        <tr class="text-center">
          <td style="width: 8%"><?php echo $index ?></td>
          <td style="white-space: nowrap; width: 20%"><?php echo $dam['name'] ?></td>
          <?php
          foreach ($dam['table'] as $year => $value) {
          ?>
            <td class="table-success">
              <a <?php echo "name='" . $dam['id'] . "'" ?> <?php echo "id='" . $year . "'" ?> data-editable-wp31-a><?php echo $value ?></a>
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
<div class='table-responsive' style="margin-top:30px">
  <H5>
    <a>
      <a href="#" id=<?php echo "import-wp-31-b" ?>><i style="font-size:20px" class="fas fa-plus-circle"></i></a> Total Water use for electricity Production (<?php echo toSup("m3") ?>)
    </a>
  </H5>
  <table id="editable_wp_31-b" class='table table-bordered table-hover'>
    <thead class='thead-dark'>
      <tr class="text-center">
        <th scope="col">#</th>
        <th scope="col">Name</th>
        <?php
        foreach ($YEAR_RANGE as $year) { ?>
          <th class='text-right'><?php echo $year ?></th>
        <?php }
        ?>
      </tr>
    </thead>
    <tbody>
      <?php
      $index = 1;
      foreach ($RiverDamList['WP3']['WP31_B'] as $key => $dam) {
      ?>
        <tr class="text-center">
          <td style="width: 8%"><?php echo $index ?></td>
          <td style="white-space: nowrap; width: 20%"><?php echo $dam['name'] ?></td>
          <?php
          foreach ($dam['table'] as $year => $value) {
          ?>
            <td class="table-success">
              <a <?php echo "name='" . $dam['id'] . "'" ?> <?php echo "id='" . $year . "'" ?> data-editable-wp31-b><?php echo $value ?></a>
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
<?php include  __DIR__ . "./../../templates/WP/weight_table/wp3_weight_table.php"; ?>
<!-- -------------------------------------------------------------------------------- -->
<!-- -------------------------------------------------------------------------------- -->
<script type="text/javascript">
  $('#import-wp-31-a').click(function() {
    $('#addDamModal_wp31').modal('show');
    $('#addDam_wp31').click(function() {
      var yearRange = <?php echo json_encode($YEAR_RANGE); ?>;
      var lastRiverId = $('#editable_wp_31-a tr td a').last().attr('name')
      var name = $('#addNewDam_wp31').val()
      var newKey = 'WP31_A_' + (parseInt(lastRiverId.toString().replace("WP31_A_", '')) + 1);
      var range = [];
      for (var i = 0; i < Object.keys(yearRange).length; i++) {
        range.push(0)
      }
      if (name !== "") {
        var newData = ['WP3', 'WP31_A', 'Electricity Production', newKey, "Dam", name, "", 'GWh', ...range];
        $.ajax({
          url: "actions/act_append.php",
          type: 'post',
          data: {
            'id': 'addNewRiver_wp31_a',
            'data': newData,
            'sheet_id': <?php echo json_encode($RIVER_DAM_LIST_SHEET); ?>,
          },
          success: function(response) {
            $('#addDamModal_wp31').modal('hide');
            $('#loading').show()
            $('#editable_wp_31-a').load(location.href + " #editable_wp_31-a", function() {
              $("#wp-WP3-score-table").load(location.href + " #wp-WP3-score-table");
              $('#loading').hide()
              $('#addNewDam_wp31').val('');
            });
          }
        });
      } else {
        alert("Cannot be empty")
      }
    })
  });
  $('#import-wp-31-b').click(function() {
    $('#addDamModal_wp31').modal('show');
    $('#addDam_wp31').click(function() {
      var yearRange = <?php echo json_encode($YEAR_RANGE); ?>;
      var lastRiverId = $('#editable_wp_31-b tr td a').last().attr('name')
      var name = $('#addNewDam_wp31').val()
      var newKey = 'WP31_B_' + (parseInt(lastRiverId.toString().replace("WP31_B_", '')) + 1);
      var range = [];
      for (var i = 0; i < Object.keys(yearRange).length; i++) {
        range.push(0)
      }
      if (name !== "") {
        var newData = ['WP3', 'WP31_B', 'Total Water use for electricity Production', newKey, "Dam", name, "", 'm3', ...range];
        $.ajax({
          url: "actions/act_append.php",
          type: 'post',
          data: {
            'id': 'addNewRiver_wp31_b',
            'data': newData,
            'sheet_id': <?php echo json_encode($RIVER_DAM_LIST_SHEET); ?>,
          },
          success: function(response) {
            $('#addDamModal_wp31').modal('hide');
            $('#loading').show()
            $('#editable_wp_31-b').load(location.href + " #editable_wp_31-b", function() {
              $("#wp-WP3-score-table").load(location.href + " #wp-WP3-score-table");
              $('#loading').hide()
              $('#addNewDam_wp31').val('');
            });
          }
        });
      } else {
        alert("Cannot be empty")
      }
    })
  });

  $('body').on('click', '[data-editable-wp31-a]', function(e) {
    e.preventDefault();
    var $el = $(this);
    var $input = $('<input type="number" id="' + $el.attr("id") + '" name="' + $el.attr("name") + '" type="text" style="min-width:100px;" class="form-control">').val($el.text());
    $el.replaceWith($input);
    var save = function() {
      var $a = $('<a id="' + $el.attr("id") + '" name="' + $el.attr("name") + '" data-editable-wp31-a />').text($input.val());
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
          $('#loading').show()
          $("#wp-WP3-score-table").load(location.href + " #wp-WP3-score-table", function() {
            $('#loading').hide()
          });
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
  ///////////////////////////////////////////////////////////////////
  ///////////////////////////////////////////////////////////////////
  $('body').on('click', '[data-editable-wp31-b]', function(e) {
    e.preventDefault();
    var $el = $(this);
    var $input = $('<input type="number" id="' + $el.attr("id") + '" type="text" style="min-width:100px;" class="form-control">').val($el.text());
    $el.replaceWith($input);
    var save = function() {
      var $a = $('<a id="' + $el.attr("id") + '" data-editable-wp31-b />').text($input.val());
      $input.replaceWith($a);
    };
    $input.one('blur', save).focus();
    ///////////////////////////////////////////////////////////////////
    function callAjax(data) {
      return $.ajax({
        url: 'actions/act_edit_cell.php',
        type: 'post',
        data: data,
        success: function(response) {
          $('#loading').show()
          $("#wp-WP3-score-table").load(location.href + " #wp-WP3-score-table", function() {
            $('#loading').hide()
          });
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