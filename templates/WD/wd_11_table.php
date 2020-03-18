<H3 style="margin-bottom:40px"><strong><?php echo "[ WD11 ] " . $WeightKeysData['WD11']['name'] ?></strong></H3>

<H4 style="margin-bottom:20px">
  <a id=<?php echo "import-wd-11" ?>><i style="font-size:20px" class="fas fa-plus-circle"></i>
    <?php echo $WeightKeysData['WD11']['id'] ?> : <?php echo $WeightKeysData['WD11']['name'] ?>
  </a>
</H4>
<!-- ------------------------------------------------- -->
<div class='table-responsive' style="margin-top:30px">
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
  $('#import-wd-11-data').click(function() {
    alert("TODO// INSERT DATA");
    // var table = $('#editable_table');
    // var body = $('#editable_tableBody');
    // var nextId = body.find('tr').length + 1;
    // table.append($('<tr><td>' + nextId + '</td><td>Sue</td></tr>'));
    // table.data('Tabledit').reload();
  });

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
          sheet_id: "SpecificInput",
          val: $input.val(),
        });
        $input.blur();
      };
    })
    $input.blur(function() {
      callAjax({
        id: $el.attr("id"),
        sheet_id: "SpecificInput",
        val: $input.val(),
      });
    })
  });
</script>