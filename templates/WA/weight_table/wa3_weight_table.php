<tr class="text-center">
  <td>WA31</td>
  <td class="table-success">
    <a id="WA31" data-editable-weight-wa3><?php echo $WeightKeysData['WA31']['weight'] ?></a>
  </td>
</tr>
<!-- -------------------------------------------------------------------------------- -->
<script type="text/javascript" src="actions/post_edit_weight.js"></script>
<script type="text/javascript">
  $('body').on('click', '[data-editable-weight-wa3]', function(e) {
    e.preventDefault();

    var $el = $(this);
    var $input = $('<input type="number" id="' + $el.attr("id") + '" type="text" style="min-width:100px;" class="form-control">').val($el.text());
    $el.replaceWith($input);

    var save = function() {
      var $a = $('<a id="' + $el.attr("id") + '" data-editable-weight-wa3 />').text($input.val());
      $input.replaceWith($a);
    };

    $input.one('blur', save).focus();
    post_edit_weight($input, $el);
  });
</script>