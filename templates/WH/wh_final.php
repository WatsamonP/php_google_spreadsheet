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
                  <td><?php echo $WeightKeysData['WH1']['id']  ?></td>
                  <td class="table-success">
                    <a id="WH1-final" data-editable-weight-wh-final><?php echo $WeightKeysData['WH1']['weight'] ?></a>
                  </td>
                </tr>
                <tr class="text-center">
                  <td><?php echo $WeightKeysData['WH2']['id'] ?></td>
                  <td class="table-success">
                    <a id="WH2-final" data-editable-weight-wh-final><?php echo $WeightKeysData['WH2']['weight'] ?></a>
                  </td>
                </tr>
                <tr class="text-center">
                  <td><?php echo $WeightKeysData['WH3']['id']  ?></td>
                  <td class="table-success">
                    <a id="WH3-final" data-editable-weight-wh-final><?php echo $WeightKeysData['WH3']['weight'] ?></a>
                  </td>
                </tr>
                <tr class="text-center">
                  <td><?php echo $WeightKeysData['WH4']['id']  ?></td>
                  <td class="table-success">
                    <a id="WH4-final" data-editable-weight-wh-final><?php echo $WeightKeysData['WH4']['weight'] ?></a>
                  </td>
                </tr>
                <tr class="text-center">
                  <td><?php echo $WeightKeysData['WH5']['id'] ?></td>
                  <td class="table-success">
                    <a id="WH5-final" data-editable-weight-wh-final><?php echo $WeightKeysData['WH5']['weight'] ?></a>
                  </td>
                </tr>
                
                <tr class="text-center">
                  <td><?php echo $WeightKeysData['WH6']['id']  ?></td>
                  <!-- TODO -->
                  <td class="table-warning">
                    <a id="WH6-final" data-editable-weight-wh-final><?php echo $WeightKeysData['WH6']['weight'] ?></a>
                  </td>
                </tr>
                <tr class="text-center">
                  <td><?php echo $WeightKeysData['WH7']['id']  ?></td>
                  <td class="table-success">
                    <a id="WH7-final" data-editable-weight-wh-final><?php echo $WeightKeysData['WH7']['weight'] ?></a>
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
        <div class="card-header text-danger">Final Index for Dim. 04</div>
        <div class="card-body">
          <div class="table-responsive">
            <table class='table table-hover' id="wh-score-table-final">
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
                <tr id="wh-final-value">
                  <!--   -->
                  <td class='text-center table-danger'></td>
                  <?php
                  if (isset($FINAL_INDICATOR_WH))
                    foreach ($FINAL_INDICATOR_WH as  $year => $score) { ?>
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
        <canvas id="whFinalGraph"></canvas>
      </div>
    </div>
  </div>
</div>
<!-- -------------------------------------------------------------------------------- -->
<script>
  function showChart(chartData = <?php echo json_encode($FINAL_INDICATOR_WH); ?>) {
    var dataset = [];
    var labels = [];
    for (const property in chartData) {
      labels.push(property)
      dataset.push(chartData[property])
    }
    var colorNames = Object.keys(window.chartColors);
    var color = Chart.helpers.color;
    var config = {
      type: 'radar',
      data: {
        labels: labels,
        datasets: [{
          // label: 'Watershed Health',
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
          text: 'Dimension 4 : Watershed Health',
          fontSize: 20
        },
        scale: {
          beginAtZero: true,
          pointLabels: {
            fontSize: 16,
          },
          ticks: {
            suggestedMin: 0.15,
          }
        },
        tooltips: {
          callbacks: {
            title: function() {},
            label: function(t, d) {
              // var xLabel = d.datasets[t.datasetIndex].label;
              var yLabel = t.yLabel;
              return (Math.round(yLabel * 100) / 100).toFixed(3);
            }
          }
        },
      }
    };
    return config;
  }

  window.onload = function() {
    window.myRadar = new Chart(document.getElementById('whFinalGraph'), showChart());
  };
</script>
<!-- -------------------------------------------------------------------------------- -->
<script type="text/javascript">
  $('body').on('click', '[data-editable-weight-wh-final]', function(e) {
    e.preventDefault();
    var $el = $(this);
    var $input = $('<input type="number" id="' + $el.attr("id") + '" type="text" style="min-width:100px;" class="form-control">').val($el.text());
    $el.replaceWith($input);
    var save = function() {
      var $a = $('<a id="' + $el.attr("id") + '" data-editable-weight-wh-final />').text($input.val());
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
          $('#wh-score-table-final').load(location.href + " #wh-score-table-final", function() {
            var newVal = $("#wh-final-value").text()
            var newArr = newVal.match(/[^\s]+/g);
            window.myRadar = new Chart(document.getElementById('whFinalGraph'), showChart(newArr));
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