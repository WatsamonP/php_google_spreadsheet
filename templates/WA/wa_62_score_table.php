<div class="container">
  <div class="row">
    <div class="col-10">
      <H4 style="margin-bottom:20px"><?php echo $WeightKeysData['WA62']['id'] ?> : <?php echo $WeightKeysData['WA62']['name'] ?></H4>
    </div>
    <div class="col-2 text-right">
      <a id="import-wa62-data"><i style="font-size:20px" class="fas fa-plus-circle"></i></a>
    </div>
  </div>
</div>
<!--  -->
<div class="container">
  <div class='table-responsive'>
    <table class='table table-bordered table-hover'>
      <thead class='thead-dark'>
        <tr class="text-center">
          <th style="width:150px;">No of Reservoirs</th>
          <th>Name</th>
          <th>Capacity</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $index = 1;
        foreach ($SpecificInput['WA6']['WA62']['reservoirs'] as $key => $item) {
        ?>
          <tr class="text-center">
            <td><?php echo $index ?></td>
            <td><?php echo $item['key'] ?></td>
            <td class="table-success">
              <a <?php echo "id='" . $item['id'] . "'" ?> data-editable-wa62-reservoirs><?php echo $item['value'] ?></a>
            </td>
          </tr>
        <?php
          $index++;
        }
        ?>
      </tbody>
    </table>
  </div>
</div>
<!-- -------------------------------------------------------------------------------- -->
<script type="text/javascript" src="actions/post_edit_weight.js"></script>
<script type="text/javascript">
  $('#import-wa62-data').click(function() {
    alert("TODO// INSERT DATA");
    // var table = $('#editable_table');
    // var body = $('#editable_tableBody');
    // var nextId = body.find('tr').length + 1;
    // table.append($('<tr><td>' + nextId + '</td><td>Sue</td></tr>'));
    // table.data('Tabledit').reload();
  });

  $('body').on('click', '[data-editable-wa62-reservoirs]', function(e) {
    e.preventDefault();

    var $el = $(this);
    var $input = $('<input type="number" id="' + $el.attr("id") + '" type="text" style="min-width:100px;" class="form-control">').val($el.text());
    $el.replaceWith($input);

    var save = function() {
      var $a = $('<a id="' + $el.attr("id") + '" data-editable-wa62-reservoirs />').text($input.val());
      $input.replaceWith($a);
    };

    $input.one('blur', save).focus();
    post_edit_weight($input, $el);
  });
</script>