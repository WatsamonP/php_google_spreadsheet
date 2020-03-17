<!-- START WEIGHT CARD -->
<div class="container">
  <div class="row justify-content-center">

    <div class="col-4 order-1">
      <div class="card border-success">
        <div class="card-header text-success">Indicators</div>
        <div class="card-body">
          <div class="table-responsive">
            <table class='table table-hover' style="margin: 0 auto;">
              <thead class='thead-dark'>
                <tr class="text-center">
                  <th>Variable</th>
                  <th>Indicators</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if (isset($WA_SET)) {
                  foreach ($WA_SET as $sKey => $set) {
                    if (isset($WeightKeysData[$sKey])) { ?>
                      <tr class="text-center">
                        <td><?php echo $sKey ?></td>
                        <td class="table-success">
                          <a <?php echo "id='" . $sKey . "_final'" ?> data-editable-weight-wa_final><?php echo $WeightKeysData[$sKey]['weight'] ?></a>
                        </td>
                      </tr>
                <?php }
                  }
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-8 order-2">
      <div class="card border-warning">
        <div class="card-header text-warning">Final Index for Dim. 01</div>
        <div class="card-body">
          <div class="table-responsive">
            <table class='table table-hover'>
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
                  if (isset($FINAL_INDICATOR))
                    foreach ($FINAL_INDICATOR as  $year => $score) { ?>
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
      <div class="chart-container" style="width:100%; display:block; margin-top:30px">
        <canvas id="waFinalGraph"></canvas>
      </div>
    </div>
  </div>
</div>
<!-- -------------------------------------------------------------------------------- -->
<script type="text/javascript" src="actions/post_edit_weight.js"></script>
<script type="text/javascript">
  $('body').on('click', '[data-editable-weight-wa_final]', function(e) {
    e.preventDefault();

    var $el = $(this);
    var $input = $('<input type="number" id="' + $el.attr("id") + '" type="text" style="min-width:100px;" class="form-control">').val($el.text());
    $el.replaceWith($input);

    var save = function() {
      var $a = $('<a id="' + $el.attr("id") + '" data-editable-weight-wa_final />').text($input.val());
      $input.replaceWith($a);
    };

    $input.one('blur', save).focus();
    post_edit_weight($input, $el);
  });
</script>
<!-- -------------------------------------------------------------------------------- -->
<script>
  var chartData = <?php echo json_encode($FINAL_INDICATOR); ?>;
  var dataset = [];
  var labels = [];
  for (const property in chartData) {
    labels.push(property)
    dataset.push(chartData[property])
  }
  // console.log(labels)

  var color = Chart.helpers.color;
  var config = {
    type: 'radar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Water Availability',
        backgroundColor: color(window.chartColors.red).alpha(0.2).rgbString(),
        borderColor: window.chartColors.red,
        pointBackgroundColor: window.chartColors.red,
        data: dataset
      }]
    },
    options: {
      legend: {
        display: false,
        // position: 'top',
        // labels: { fontSize: 18 }
      },
      title: {
        display: true,
        text: 'Dimension 1 : Water Availability',
        fontSize: 20
      },
      scale: {
        beginAtZero: true,
        pointLabels: {
          fontSize: 16,
        }
      },
    }
  };

  window.onload = function() {
    window.myRadar = new Chart(document.getElementById('waFinalGraph'), config);
  };
  var colorNames = Object.keys(window.chartColors);
</script>