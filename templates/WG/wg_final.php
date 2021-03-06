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
                <tr class="text-center">
                  <td><?php echo $WeightKeysData['WG1']['id']  ?></td>
                  <td style="cursor: pointer" class="table-success">
                    <a id="WG1-final" data-editable-weight-wg-final><?php echo $WeightKeysData['WG1']['weight'] ?></a>
                  </td>
                </tr>
                <tr class="text-center">
                  <td><?php echo $WeightKeysData['WG2']['id'] ?></td>
                  <td style="cursor: pointer" class="table-success">
                    <a id="WG2-final" data-editable-weight-wg-final><?php echo $WeightKeysData['WG2']['weight'] ?></a>
                  </td>
                </tr>
                <tr class="text-center">
                  <td><?php echo $WeightKeysData['WG3']['id']  ?></td>
                  <td style="cursor: pointer" class="table-success">
                    <a id="WG3-final" data-editable-weight-wg-final><?php echo $WeightKeysData['WG3']['weight'] ?></a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="col-8 order-2">
      <div class="card border-danger">
        <div class="card-header text-danger">Final Index for Dim. 05</div>
        <div class="card-body">
          <div class="table-responsive">
            <table class='table table-hover' id="wg-score-table-final">
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
                <tr id="wg-final-value">
                  <!--   -->
                  <td class='text-center table-danger'></td>
                  <?php
                  if (isset($FINAL_INDICATOR_WG))
                    foreach ($FINAL_INDICATOR_WG as  $year => $score) { ?>
                    <td class='text-center table-danger'>
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
        <canvas id="wgFinalGraph"></canvas>
      </div>
    </div>
  </div>
</div>
<!-- -------------------------------------------------------------------------------- -->
<script>
  function showChart(chartData = <?php echo json_encode($FINAL_INDICATOR_WG); ?>) {
    var colorNames = Object.keys(window.chartColors);
    var color = Chart.helpers.color;
    var colors = [
      window.chartColors.red,
      window.chartColors.blue,
      window.chartColors.green,
      window.chartColors.yellow,
      window.chartColors.orange
    ]

    var dataset = [];
    var labels = [];
    var colorSet = [];
    var colorSetAlpha = [];

    var i = 0;
    for (const property in chartData) {
      labels.push(property)
      dataset.push(chartData[property])
      colorSet.push(colors[i % 5])
      colorSetAlpha.push(color(colors[i % 5]).alpha(0.2).rgbString())
      i++
    }

    var config = {
      type: 'bar',
      data: {
        labels: labels,
        datasets: [{
          data: dataset,
          backgroundColor: colorSetAlpha,
          borderColor: colorSet,
          borderWidth: 1
        }]
      },
      options: {
        legend: {
          display: false
        },
        title: {
          display: true,
          text: 'Dimension 5 : Water Governance',
          fontSize: 20
        },
        scales: {
          xAxes: [{
            scaleLabel: {
              fontSize: 14,
              display: true,
              labelString: 'YEAR'
            }
          }],
          yAxes: [{
            scaleLabel: {
              fontSize: 14,
              display: true,
              labelString: 'INDEX'
            },
            ticks: {
              beginAtZero: true
            }
          }]
        }
      }
    };
    return config;
  }

  window.onload = function() {
    var ctx = document.getElementById('wgFinalGraph').getContext('2d');
    window.chart = new Chart(ctx, showChart());
  };
</script>
<!-- -------------------------------------------------------------------------------- -->
<script type="text/javascript">
  $('body').on('click', '[data-editable-weight-wg-final]', function(e) {
    e.preventDefault();
    var $el = $(this);
    var $input = $('<input type="number" id="' + $el.attr("id") + '" type="text" style="min-width:100px;" class="form-control">').val($el.text());
    $el.replaceWith($input);
    var save = function() {
      var $a = $('<a id="' + $el.attr("id") + '" data-editable-weight-wg-final />').text($input.val());
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
          $('#loadingPink').show()
          $('#wg-score-table-final').load(location.href + " #wg-score-table-final", function() {
            var newVal = $("#wg-final-value").text()
            var newArr = newVal.match(/[^\s]+/g);
            window.myRadar = new Chart(document.getElementById('wgFinalGraph').getContext('2d'), showChart(newArr));
            $('#loadingPink').hide()
          });
        }
      })
    }
    ////////////////////////////////////////////////////////////////////
    $input.keydown(function(e) {
      if (e.keyCode == 13 | e.keyCode == 9) {
        callAjax({
          id: $el.attr("id").replace('-final', ''),
          sheet_id: <?php echo json_encode($WEIGHT_KEY_SHEET); ?>,
          val: $input.val(),
        });
        $input.blur();
      };
    })
    $input.blur(function() {
      callAjax({
        id: $el.attr("id").replace('-final', ''),
        sheet_id: <?php echo json_encode($WEIGHT_KEY_SHEET); ?>,
        val: $input.val(),
      });
    })
  });
</script>