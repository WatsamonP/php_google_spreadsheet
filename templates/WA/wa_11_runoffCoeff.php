<div style="margin-top:30px" class='table-responsive'>
  <H5>
    <i class="fas fa-table"></i>
    <?php echo $SpecificInputYears['WA1']['WA11']['runoffCoeff']['key'] ?>
  </H5>
  <!--  -->
  <table id="tableTab" class='table table-hover'>
    <thead class='thead-dark'>
      <tr>
        <th scope='col' class='text-center'>Year</th>
        <?php
        foreach ($YEAR_RANGE as $year) {
        ?>
          <th class='text-right'><?php echo $year ?></th>
        <?php }
        ?>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="text-center table-success"><i class="fas fa-edit"></i></td>
        <?php foreach ($SpecificInputYears['WA1']['WA11']['runoffCoeff']['table'] as $key => $_data) { ?>
          <td id="EditText" class='text-right table-success'>
            <a name='runoffCoeff' <?php echo "id='" . $key . "'" ?> data-editable-runoff-coeff><?php echo number_format($_data, 2, '.', '')  ?></a>
          </td>
        <?php }
        ?>
      </tr>
    </tbody>
  </table>
</div>
<!-- -------------------------------------------------------------------------------- -->
<script type="text/javascript">
  $('body').on('click', '[data-editable-runoff-coeff]', function(e) {
    e.preventDefault();
    var $el = $(this);
    var $input = $('<input type="number" id="' + $el.attr("id") + '" name="' + $el.attr("name") + '" type="text" style="min-width:100px;" class="form-control">').val($el.text());
    $el.replaceWith($input);
    var save = function() {
      var $a = $('<a id="' + $el.attr("id") + '" name="' + $el.attr("name") + '" data-editable-runoff-coeff />').text($input.val());
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
          $("#wa-WA11-score-table").load(location.href + " #wa-WA11-score-table");
          $("#wa-WA1-score-table").load(location.href + " #wa-WA1-score-table");
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