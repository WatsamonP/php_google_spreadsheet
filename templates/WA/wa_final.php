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
                        <td style="cursor: pointer" class="table-success">
                          <a <?php echo "id='" . $sKey . "-final'" ?> data-editable-weight-wa-final><?php echo $WeightKeysData[$sKey]['weight'] ?></a>
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
      <div class="card border-danger">
        <div class="card-header text-danger">Final Index for Dim. 01</div>
        <div class="card-body">
          <div class="table-responsive">
            <table class='table table-hover' id="wa-score-table-final">
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
                <tr id="wa-final-value">
                  <!--   -->
                  <td class='text-center table-danger'></td>
                  <?php
                  if (isset($FINAL_INDICATOR_WA))
                    foreach ($FINAL_INDICATOR_WA as  $year => $score) { ?>
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
        <canvas id="waFinalGraph"></canvas>
      </div>
    </div>
  </div>
</div>
<!-- -------------------------------------------------------------------------------- -->
<script>
  function showChart(chartData = <?php echo json_encode($FINAL_INDICATOR_WA); ?>) {
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
          text: 'Dimension 1 : Water Availability',
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
    var ctx = document.getElementById('waFinalGraph').getContext('2d');
    window.chart = new Chart(ctx, showChart());
  };
</script>
<!-- -------------------------------------------------------------------------------- -->
<script type="text/javascript">
  $('body').on('click', '[data-editable-weight-wa-final]', function(e) {
    e.preventDefault();
    var $el = $(this);
    var $input = $('<input type="number" id="' + $el.attr("id") + '" type="text" style="min-width:100px;" class="form-control">').val($el.text());
    $el.replaceWith($input);
    var save = function() {
      var $a = $('<a id="' + $el.attr("id") + '" data-editable-weight-wa-final />').text($input.val());
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
          $('#wa-score-table-final').load(location.href + " #wa-score-table-final", function() {
            var newVal = $("#wa-final-value").text()
            var newArr = newVal.match(/[^\s]+/g);
            window.chart = new Chart(document.getElementById('waFinalGraph').getContext('2d'), showChart(newArr));
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