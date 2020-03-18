<tr class="text-center">
  <td>WD51</td>
  <td class="table-success">
    <a id="WD51" data-editable-weight-wd5><?php echo $WeightKeysData['WD51']['weight'] ?></a>
  </td>
</tr>
<tr class="text-center">
  <td>WD52</td>
  <td class="table-success">
    <a id="WD52" data-editable-weight-wd5><?php echo $WeightKeysData['WD52']['weight'] ?></a>
  </td>
</tr>
<!-- -------------------------------------------------------------------------------- -->
<script type="text/javascript">
  $('body').on('click', '[data-editable-weight-wd5]', function(e) {
    e.preventDefault();
    var $el = $(this);
    var $input = $('<input type="number" id="' + $el.attr("id") + '" type="text" style="min-width:100px;" class="form-control">').val($el.text());
    $el.replaceWith($input);
    var save = function() {
      var $a = $('<a id="' + $el.attr("id") + '" data-editable-weight-wd5 />').text($input.val());
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
          $("#wd-WD5-score-table").load(location.href + " #wd-WD5-score-table");
        },
      })
    }
    ////////////////////////////////////////////////////////////////////
    $input.keydown(function(e) {
      if (e.keyCode == 13 | e.keyCode == 9) {
        callAjax({
          id: $el.attr("id"),
          sheet_id: "WeightKey",
          val: $input.val(),
        });
        $input.blur();
      };
    })
    $input.blur(function() {
      callAjax({
        id: $el.attr("id"),
        sheet_id: "WeightKey",
        val: $input.val(),
      });
    })
  });
</script>