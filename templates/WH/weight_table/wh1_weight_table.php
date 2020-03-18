<!-- START WEIGHT CARD -->
<div class="container">
  <div class="row justify-content-center">
    <div class="col-3 order-1">
      <div class="card border-success mb-3">
        <div class="card-header text-success">Weight</div>
        <div class="card-body">
          <div class="table-responsive">
            <table class='table table-hover' style="margin: 0 auto;">
              <thead class='thead-dark'>
                <tr class="text-center">
                  <th>Variable</th>
                  <th>Weight</th>
                </tr>
              </thead>
              <tbody>
                <tr class="text-center">
                  <td>WH11</td>
                  <td class="table-success">
                    <a id="WH11" data-editable-weight-wh1><?php echo $WeightKeysData['WH11']['weight'] ?></a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-9 order-2">
      <div class="card border-warning mb-3">
        <div class="card-header text-warning">Score</div>
        <div class="card-body">
          <div class="table-responsive">
            <table id="wh-WH1-score-table" class='table table-hover' style="margin: 0 auto;">
              <thead class='thead-dark'>
                <tr>
                  <th scope='col' class='text-center'>Year</th>
                  <?php
                  foreach ($YEAR_RANGE as $year) {
                  ?>
                    <th class='text-center'><?php echo $year ?></th>
                  <?php }
                  ?>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <!--   -->
                  <td class='text-center table-warning'></td>
                  <?php
                  if (isset($FINAL_SCORE_WH['WH1']))
                    foreach ($FINAL_SCORE_WH['WH1'] as  $year => $score) { ?>
                    <td class='text-center table-warning'>
                      <?php echo number_format($score, 2, '.', '')  ?>
                    </td>
                  <?php }
                  ?>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- END WEIGHT CARD -->
<!-- -------------------------------------------------------------------------------- -->
<script type="text/javascript">
  $('body').on('click', '[data-editable-weight-wh1]', function(e) {
    e.preventDefault();
    var $el = $(this);
    var $input = $('<input type="number" id="' + $el.attr("id") + '" type="text" style="min-width:100px;" class="form-control">').val($el.text());
    $el.replaceWith($input);
    var save = function() {
      var $a = $('<a id="' + $el.attr("id") + '" data-editable-weight-wh1 />').text($input.val());
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
          $("#wh-WH1-score-table").load(location.href + " #wh-WH1-score-table");
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