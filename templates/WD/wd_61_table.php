<H3 style="margin-bottom:40px"><strong><?php echo "[ WD61 ] " . $WeightKeysData['WD61']['name'] ?></strong></H3>

<H4 style="margin-bottom:20px">
  <a id=<?php echo "import-wd-11" ?>><i style="font-size:20px" class="fas fa-edit"></i>
    <?php echo $WeightKeysData['WD61']['id'] ?> : <?php echo $WeightKeysData['WD61']['name'] ?>
  </a>
</H4>
<!-- ------------------------------------------------- -->
<div class='table-responsive' style="margin-top:30px">
  <table class='table table-bordered table-hover'>
    <thead class='thead-dark'>
      <tr class="text-center">
        <th scope='col' class='text-center'>Year</th>
        <?php
        foreach ($YEAR_RANGE as $year) {
        ?>
          <th class='text-center'><?php echo $year ?></th>
        <?php }
        ?>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="white-space: nowrap;"><?php echo $SpecificInputYears['WD6']['WD61']['stdRainfall']['key'] ?></td>
        <?php foreach ($SpecificInputYears['WD6']['WD61']['stdRainfall']['table'] as $year => $value) {
        ?>
          <td id="EditText" class='text-right table-success'>
            <a name="stdRainfall" <?php echo "id='" . $year . "'" ?> data-editable-wd61-stdRainfall><?php echo number_format($value, 2, '.', '')  ?></a>
          </td>
        <?php } ?>
      </tr>
      <!--  -->
      <tr>
        <td style="white-space: nowrap;"><?php echo $SpecificInputYears['WD6']['WD61']['meanRainfall']['key'] ?></td>
        <?php foreach ($SpecificInputYears['WD6']['WD61']['meanRainfall']['table'] as $year => $value) {
        ?>
          <td id="EditText" class='text-right table-success'>
            <a name="meanRainfall" <?php echo "id='" . $year . "'" ?> data-editable-wd61-meanRainfall><?php echo number_format($value, 2, '.', '')  ?></a>
          </td>
        <?php } ?>
      </tr>
    </tbody>
  </table>
</div>
<?php include  __DIR__ . "./../../templates/utils/hr.html"; ?>
<!-- ------------------------------------------------- -->
<?php include  __DIR__ . "./../../templates/WD/weight_table/wd6_weight_table.php"; ?>
<!-- -------------------------------------------------------------------------------- -->
<!-- -------------------------------------------------------------------------------- -->
<script type="text/javascript">
  $('body').on('click', '[data-editable-wd61-stdRainfall]', function(e) {
    e.preventDefault();
    var $el = $(this);
    var $input = $('<input type="number" id="' + $el.attr("id") + '" name="' + $el.attr("name") + '" type="text" style="min-width:100px;" class="form-control">').val($el.text());
    $el.replaceWith($input);
    var save = function() {
      var $a = $('<a id="' + $el.attr("id") + '" name="' + $el.attr("name") + '" data-editable-wd61-stdRainfall />').text($input.val());
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
          $("#wd-WD6-score-table").load(location.href + " #wd-WD6-score-table");
        },
      })
    }
    ////////////////////////////////////////////////////////////////////
    $input.keydown(function(e) {
      if (e.keyCode == 13 | e.keyCode == 9) {
        callAjax({
          id: $el.attr("name"),
          sheet_id: "SpecificInputYears",
          year: $el.attr("id"),
          val: $input.val(),
        });
        $input.blur();
      };
    })
    $input.blur(function() {
      callAjax({
        id: $el.attr("name"),
        sheet_id: "SpecificInputYears",
        year: $el.attr("id"),
        val: $input.val(),
      });
    })
  });
// <!-- -------------------------------------------------------------------------------- -->
  $('body').on('click', '[data-editable-wd61-meanRainfall]', function(e) {
    e.preventDefault();
    var $el = $(this);
    var $input = $('<input type="number" id="' + $el.attr("id") + '" name="' + $el.attr("name") + '" type="text" style="min-width:100px;" class="form-control">').val($el.text());
    $el.replaceWith($input);
    var save = function() {
      var $a = $('<a id="' + $el.attr("id") + '" name="' + $el.attr("name") + '" data-editable-wd61-meanRainfall />').text($input.val());
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
          $("#wd-WD6-score-table").load(location.href + " #wd-WD6-score-table");
        },
      })
    }
    ////////////////////////////////////////////////////////////////////
    $input.keydown(function(e) {
      if (e.keyCode == 13 | e.keyCode == 9) {
        callAjax({
          id: $el.attr("name"),
          sheet_id: "SpecificInputYears",
          year: $el.attr("id"),
          val: $input.val(),
        });
        $input.blur();
      };
    })
    $input.blur(function() {
      callAjax({
        id: $el.attr("name"),
        sheet_id: "SpecificInputYears",
        year: $el.attr("id"),
        val: $input.val(),
      });
    })
  });
</script>