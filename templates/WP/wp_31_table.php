<H3 style="margin-bottom:40px"><strong><?php echo "[ WP3 ] " . $WeightKeysData['WP3']['name'] ?></strong></H3>
<!-- ------------------------------------------------- -->
<H4 style="margin-bottom:20px"><?php echo $WeightKeysData['WP31']['id'] ?> : <?php echo $WeightKeysData['WP31']['name'] ?></H4>
<!-- ------------------------------------------------- -->
<div class='table-responsive' style="margin-top:30px">
  <H5>
    <a id=<?php echo "import-wp-31-a" ?>><i style="font-size:20px" class="fas fa-plus-circle"></i></a> Electricity Production (<?php echo toSup("GWh") ?>)
  </H5>
  <table class='table table-bordered table-hover'>
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
    <a id=<?php echo "import-wp-31-b" ?>><i style="font-size:20px" class="fas fa-plus-circle"></i></a> Total Water use for electricity Production (<?php echo toSup("m3") ?>)
  </H5>
  <table class='table table-bordered table-hover'>
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
    alert("TODO// INSERT DATA");
    // var table = $('#editable_table');
    // var body = $('#editable_tableBody');
    // var nextId = body.find('tr').length + 1;
    // table.append($('<tr><td>' + nextId + '</td><td>Sue</td></tr>'));
    // table.data('Tabledit').reload();
  });
  $('#import-wp-31-b').click(function() {
    alert("TODO// INSERT DATA");
    // var table = $('#editable_table');
    // var body = $('#editable_tableBody');
    // var nextId = body.find('tr').length + 1;
    // table.append($('<tr><td>' + nextId + '</td><td>Sue</td></tr>'));
    // table.data('Tabledit').reload();
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
          $("#wp-WP3-score-table").load(location.href + " #wp-WP3-score-table");
        },
      })
    }
    ////////////////////////////////////////////////////////////////////
    $input.keydown(function(e) {
      if (e.keyCode == 13 | e.keyCode == 9) {
        callAjax({
          id: $el.attr("name"),
          sheet_id: "RiverDamList",
          year: $el.attr("id"),
          val: $input.val(),
        });
        $input.blur();
      };
    })
    $input.blur(function() {
      callAjax({
        id: $el.attr("name"),
        sheet_id: "RiverDamList",
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
          $("#wp-WP3-score-table").load(location.href + " #wp-WP3-score-table");
        },
      })
    }
    ////////////////////////////////////////////////////////////////////
    $input.keydown(function(e) {
      if (e.keyCode == 13 | e.keyCode == 9) {
        callAjax({
          id: $el.attr("name"),
          sheet_id: "RiverDamList",
          year: $el.attr("id"),
          val: $input.val(),
        });
        $input.blur();
      };
    })
    $input.blur(function() {
      callAjax({
        id: $el.attr("name"),
        sheet_id: "RiverDamList",
        year: $el.attr("id"),
        val: $input.val(),
      });
    })
  });
</script>