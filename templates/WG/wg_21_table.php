<H3 style="margin-bottom:40px"><strong><?php echo "[ WG2 ] " . $WeightKeysData['WG2']['name'] ?></strong></H3>
<!-- ------------------------------------------------- -->
<H4 style="margin-bottom:20px">
  <a id=<?php echo "import-wg-21" ?>><i style="font-size:20px" class="fas fa-user-plus"></i>
    <?php echo $WeightKeysData['WG21']['id'] ?> : <?php echo $WeightKeysData['WG21']['name'] ?>
  </a>
</H4>
<!-- ------------------------------------------------- -->
<div style="margin-top:30px" class='table-responsive'>
  <table id="tableTab" class='table table-hover'>
    <thead class='thead-dark'>
      <tr>
        <th scope='col' class='text-center'>#</th>
        <th scope='col' class='text-left' style="white-space: nowrap;">Name of the respondent</th>
        <?php
        for ($i = 1; $i <= sizeof($RespondentList['WG2']['WG21'][array_key_first($RespondentList['WG2']['WG21'])]['score']); $i++) {
        ?>
          <th class='text-center'><?php echo "Q" . $i ?></th>
        <?php }
        ?>
      </tr>
    </thead>
    <tbody>
      <?php
      $index = 1;
      foreach ($RespondentList['WG2']['WG21'] as $rId => $res) { ?>
        <tr>
          <td class="text-center"><?php echo $index; ?></td>
          <td class="text-left"><?php echo $res['name']; ?></td>
          <?php foreach ($res['score'] as $q => $value) { ?>
            <td id="EditText" class='text-center table-success'>
              <a name="<?php echo $rId; ?>" id="<?php echo $q; ?>" data-editable-wg21><?php echo $value; ?></a>
            </td>
          <?php }
          ?>
        </tr>
      <?php $index++;
      } ?>
    </tbody>
  </table>
</div>
<?php include  __DIR__ . "./../../templates/utils/hr.html"; ?>
<!-- ------------------------------------------------- -->
<?php include  __DIR__ . "./../../templates/WG/weight_table/wg2_weight_table.php"; ?>
<!-- -------------------------------------------------------------------------------- -->
<script type="text/javascript">
  $('body').on('click', '[data-editable-wg21]', function(e) {
    e.preventDefault();
    var $el = $(this);
    var $input = $('<input type="number" id="' + $el.attr("id") + '" name="' + $el.attr("name") + '" type="text" style="min-width:100px;" class="form-control">').val($el.text());
    $el.replaceWith($input);
    var save = function() {
      var $a = $('<a id="' + $el.attr("id") + '" name="' + $el.attr("name") + '" data-editable-wg21 />').text($input.val());
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
          $("#wg-WG2-score-table").load(location.href + " #wg-WG2-score-table");
        },
      })
    }
    ////////////////////////////////////////////////////////////////////
    $input.keydown(function(e) {
      console.log($el.attr("name"), $el.attr("id"));
      if (e.keyCode == 13 | e.keyCode == 9) {
        callAjax({
          id: $el.attr("name"),
          sheet_id: "RespondentList",
          year: $el.attr("id"),
          val: $input.val(),
        });
        $input.blur();
      };
    })
    $input.blur(function() {
      callAjax({
        id: $el.attr("name"),
        sheet_id: "RespondentList",
        year: $el.attr("id"),
        val: $input.val(),
      });
    })
  });
</script>