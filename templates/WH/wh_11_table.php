<H3 style="margin-bottom:40px"><strong><?php echo "[ WH1 ] " . $WeightKeysData['WH1']['name'] ?></strong></H3>

<H4 style="margin-bottom:20px">
  <a id=<?php echo "import-wh-11" ?>><i style="font-size:20px" class="fas fa-plus-circle"></i>
    <?php echo $WeightKeysData['WH11']['id'] ?> : <?php echo $WeightKeysData['WH11']['name'] ?>
  </a>
</H4>
<!-- ------------------------------------------------- -->
<div class='table-responsive' style="margin-top:30px">
  <table class='table table-bordered table-hover'>
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
  $('#import-wh-11-data').click(function() {
    alert("TODO// INSERT DATA");
    // var table = $('#editable_table');
    // var body = $('#editable_tableBody');
    // var nextId = body.find('tr').length + 1;
    // table.append($('<tr><td>' + nextId + '</td><td>Sue</td></tr>'));
    // table.data('Tabledit').reload();
  });

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