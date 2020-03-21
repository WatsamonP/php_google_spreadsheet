<div style="margin-top:30px" class='table-responsive'>
  <H5>
    <i class="fas fa-table"></i>
    <?php echo $SpecificInputYears['WP1']['WP11']['exchangeValueOfOneD']['key'] ?>
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
        <?php foreach ($SpecificInputYears['WP1']['WP11']['exchangeValueOfOneD']['table'] as $key => $_data) { ?>
          <td style="cursor: pointer" id="EditText" class='text-right table-success'>
            <a name='exchangeValueOfOneD' <?php echo "id='" . $key . "'" ?> data-editable-exchange><?php echo number_format($_data, 2, '.', '')  ?></a>
          </td>
        <?php }
        ?>
      </tr>
    </tbody>
  </table>
</div>
<!-- -------------------------------------------------------------------------------- -->
<script type="text/javascript">
  $('body').on('click', '[data-editable-exchange]', function(e) {
    e.preventDefault();
    var $el = $(this);
    var $input = $('<input type="number" id="' + $el.attr("id") + '" name="' + $el.attr("name") + '" type="text" style="min-width:100px;" class="form-control">').val($el.text());
    $el.replaceWith($input);
    var save = function() {
      var $a = $('<a id="' + $el.attr("id") + '" name="' + $el.attr("name") + '" data-editable-exchange />').text($input.val());
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
          $("#wp-WP1-score-table").load(location.href + " #wp-WP1-score-table", function() {
            $('#loading').hide()
          });
        },
      })
    }
    ////////////////////////////////////////////////////////////////////
    $input.keydown(function(e) {
      if (e.keyCode == 13 | e.keyCode == 9) {
        console.log($el.attr("name"), $el.attr("id"), $input.val())
        callAjax({
          id: $el.attr("name"),
          sheet_id: <?php echo json_encode($SPECIFIC_INPUT_YEARS_SHEET); ?>,
          year: $el.attr("id"),
          val: $input.val(),
        });
        $input.blur();
      };
    })
    $input.blur(function() {
      callAjax({
        id: $el.attr("name"),
        sheet_id: <?php echo json_encode($SPECIFIC_INPUT_YEARS_SHEET); ?>,
        year: $el.attr("id"),
        val: $input.val(),
      });
    })
  });
</script>