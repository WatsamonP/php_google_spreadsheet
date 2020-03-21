<tr class="text-center">
  <td>WA21</td>
  <td style="cursor: pointer" class="table-success">
    <a id="WA21" data-editable-weight-wa2><?php echo $WeightKeysData['WA21']['weight'] ?></a>
  </td>
</tr>
<!-- -------------------------------------------------------------------------------- -->
<script type="text/javascript">
  $('body').on('click', '[data-editable-weight-wa2]', function(e) {
    e.preventDefault();
    var $el = $(this);
    var $input = $('<input type="number" id="' + $el.attr("id") + '" type="text" style="min-width:100px;" class="form-control">').val($el.text());
    $el.replaceWith($input);
    var save = function() {
      var $a = $('<a id="' + $el.attr("id") + '" data-editable-weight-wa2 />').text($input.val());
      $input.replaceWith($a);
    };
    $input.one('blur', save).focus();
    ////////////////////////////////////////////////////////////////////
    function callAjax(data) {
      return $.ajax({
        url: 'actions/act_edit_weight.php',
        type: 'post',
        data: data,
        success: function(response) {
          $('#loading').show()
          $("#wa-WA2-score-table").load(location.href + " #wa-WA2-score-table", function() {
            $('#loading').hide()
          });
        },
      })
    }
    ////////////////////////////////////////////////////////////////////
    $input.keydown(function(e) {
      if (e.keyCode == 13 | e.keyCode == 9) {
        callAjax({
          id: $el.attr("id"),
          sheet_id: <?php echo json_encode($WEIGHT_KEY_SHEET); ?>,
          val: $input.val(),
        });
        $input.blur();
      };
    })
    $input.blur(function() {
      callAjax({
        id: $el.attr("id"),
        sheet_id: <?php echo json_encode($WEIGHT_KEY_SHEET); ?>,
        val: $input.val(),
      });
    })
  });
</script>