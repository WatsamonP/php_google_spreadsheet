<tr class="text-center">
  <td>WH41</td>
  <td style="cursor: pointer" class="table-success">
    <a id="WH41" data-editable-weight-wh4><?php echo $WeightKeysData['WH41']['weight'] ?></a>
  </td>
</tr>
<!-- -------------------------------------------------------------------------------- -->
<script type="text/javascript">
  $('body').on('click', '[data-editable-weight-wh4]', function(e) {
    e.preventDefault();
    var $el = $(this);
    var $input = $('<input type="number" id="' + $el.attr("id") + '" type="text" style="min-width:100px;" class="form-control">').val($el.text());
    $el.replaceWith($input);
    var save = function() {
      var $a = $('<a id="' + $el.attr("id") + '" data-editable-weight-wh4 />').text($input.val());
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
          $("#wh-WH4-score-table").load(location.href + " #wh-WH4-score-table", function() {
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