<?php $THIS_KEY = "annualAverageRainfall"; ?>

<!-- annualAverageRainfall is on row 2 -- INDEX = 1 -->
<div style="margin-top:30px" class='table-responsive'>
  <H5>
    <i class="fas fa-table"></i>
    <?php echo $RawSpecificInputYears[1]['key'] ?> (<?php echo toSup($RawSpecificInputYears[1]['unit']) ?>)
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
        <?php foreach ($SpecificInputYears[1] as $key => $_data) { ?>
          <td id="EditText" class='text-right table-success'>
            <a <?php echo "name='" . $THIS_KEY . "'" ?> <?php echo "id='" . $key . "'" ?> data-editable-aar><?php echo number_format($_data, 2, '.', '')  ?></a>
          </td>
        <?php }
        ?>
      </tr>
    </tbody>
  </table>
</div>
<!-- -------------------------------------------------------------------------------- -->
<script type="text/javascript" src="actions/post_editable_cell.js"></script>
<script type="text/javascript">
  $('body').on('click', '[data-editable-aar]', function(e) {
    e.preventDefault();

    var $el = $(this);
    var $input = $('<input type="number" id="' + $el.attr("id") + '" type="text" style="min-width:100px;" class="form-control">').val($el.text());
    $el.replaceWith($input);

    var save = function() {
      var $a = $('<a id="' + $el.attr("id") + '" data-editable-aar />').text($input.val());
      $input.replaceWith($a);
    };

    $input.one('blur', save).focus();
    post_editable_cell($input, $el);
  });
</script>