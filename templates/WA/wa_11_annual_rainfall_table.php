<?php $THIS_KEY = "annualAverageRainfall" ?>
<div style="margin-top:30px" class='table-responsive'>
  <H5>
    <i class="fas fa-table"></i>
    <?php echo $RawUserInput[1]['key'] ?> (<?php echo toSup($RawUserInput[1]['unit']) ?>)
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
        <?php foreach ($UserInput[1] as $key => $UserInput_data) { ?>
          <td id="EditText" class='text-right table-success'>
            <a <?php echo "name='" . $THIS_KEY . "'" ?> <?php echo "id='" . $key . "'" ?> data-editable-aar><?php echo number_format($UserInput_data, 2, '.', '')  ?></a>
          </td>
        <?php }
        ?>
      </tr>
    </tbody>
  </table>
</div>
<!--  -->
<script type="text/javascript">
  $('body').on('click', '[data-editable-aar]', function() {
    var $el = $(this);

    var $input = $('<input type="number" id="' + $el.attr("id") + '" name="' + $el.attr("name") + '" type="text" style="min-width:100px;" class="form-control">').val($el.text());
    $el.replaceWith($input);

    var save = function() {
      var $a = $('<a id="' + $el.attr("id") + '" data-editable-aar name="' + $el.attr("name") + '" />').text($input.val());
      $input.replaceWith($a);
    };

    $input.one('blur', save).focus();
    $input.keydown(function(e) {
      $val = $input.val()
      // 13 = Enter, 9 = Tab
      if (e.keyCode == 13 | e.keyCode == 9) {
        var data = {
          id: $el.attr("name"),
          sheet_id: 'UserInput',
          year: $el.attr("id"),
          val: $val
        };
        $.ajax({
          url: 'actions/act_key_cell_post.php',
          type: 'post',
          data: data,
          success: function(response) {
            $input.blur();
          }
        })
      }
    })
    $input.blur(function() {
      var data = {
        id: $el.attr("name"),
        sheet_id: 'UserInput',
        year: $el.attr("id"),
        val: $input.val()
      };
      $.ajax({
        url: 'actions/act_key_cell_post.php',
        type: 'post',
        data: data,
        success: function(response) {}
      })
    });
  });
</script>