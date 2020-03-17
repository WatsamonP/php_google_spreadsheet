<tr class="text-center">
  <td>WA21</td>
  <td class="table-success">
    <a id="WA21" data-editable-weight-wa2><?php echo $WeightKeysData['WA21']['weight'] ?></a>
  </td>
</tr>
<!-- -------------------------------------------------------------------------------- -->
<script type="text/javascript" src="actions/post_edit_weight.js"></script>
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
    post_edit_weight($input, $el);
  });
</script>