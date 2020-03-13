<?php $THIS_KEY = "runoffCoeff" ?>

<div style="margin-top:30px" class='table-responsive'>
  <H5>
    <i class="fas fa-clipboard-list"></i> #Table Name#
  </H5>
  <!--  -->
  <table class='table table-hover' style="display: block !important;">
    <thead class='thead-dark'>
      <tr>
        <th scope='col' class='text-left'>Year</th>
        <?php
        foreach ($YEAR_RANGE as $year) {
        ?>
          <th class='text-right'><?php echo $year ?></th>
        <?php }
        ?>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($WA11_TB as $key => $WA11_item) { ?>
        <tr>
          <?php
          if ($key == $THIS_KEY) { ?>
            <td class='table-success' style="white-space: nowrap;">
              <i class="fas fa-edit"></i> <?php echo $WA11_item['key'] ?>
            </td>
          <?php } else { ?>
            <td style="white-space: nowrap;"><?php echo $WA11_item['key'] ?></td>
          <?php
          } ?>
          <?php foreach ($WA11_item['table'] as $item_key => $WA11_data) {
            if ($key == $THIS_KEY) {
          ?><td class='text-right table-success'>
                <a <?php echo "name='" . $THIS_KEY . "'" ?> <?php echo "id='" . $item_key . "'" ?> data-editable-score><?php echo number_format($WA11_data, 2, '.', '')  ?></a>
              </td>
            <?php
            } else { ?>
              <td class='text-right'><?php echo number_format($WA11_data, 2, '.', '')  ?></td>
            <?php }
            ?>
          <?php }
          ?>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<!--  -->
<script type="text/javascript">
  $('body').on('click', '[data-editable-score]', function() {
    var $el = $(this);

    var $input = $('<input type="number" id="' + $el.attr("id") + '" name="' + $el.attr("name") + '"  type="text" style="min-width:100px;" class="form-control">').val($el.text());
    $el.replaceWith($input);

    var save = function() {
      var $a = $('<a id="' + $el.attr("id") + '" data-editable-score name="' + $el.attr("name") + '" />').text($input.val());
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