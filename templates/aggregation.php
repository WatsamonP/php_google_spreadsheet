<?php
require __DIR__ . "./../config.php";
require __DIR__ . "./../php/brief.php";
require __DIR__ . "./../php/aggregation.php";
require_once __DIR__ . "./../constants/word.php";
?>
<!--  -->
<nav class="navbar navbar-light bg-light">
  <a class="navbar-brand" href="#" style="font-size:30px">
    <div id="sidebarCollapse">
      <i id="sidebarIcon" class="fas fa-caret-square-left"></i>
      AGGREGATION
    </div>
  </a>
</nav>
<!-- NUMERTIC ALERT -->
<?php include __DIR__ . "./../templates/alert/numeric_alert.html" ?>

<div class='table-responsive'>
  <table class="table">
    <thead class="thead-dark">
      <tr class="text-center">
        <th style="white-space: nowrap;" scope="col"></th>
        <?php
        foreach ($YEAR_RANGE as $year) { ?> <th scope="col"><?php echo $year; ?></th>
        <?php } ?>
      </tr>
    </thead>
    <tbody class="text-center">
      <tr>
        <td>Dimension 1</td>
        <?php foreach ($FINAL_INDICATOR_WA as $value) { ?>
          <td><?php echo number_format($value, 2, '.', ''); ?></td>
        <?php } ?>
      </tr>
      <tr>
        <td>Dimension 2</td>
        <?php foreach ($FINAL_INDICATOR_WP as $value) { ?>
          <td><?php echo number_format($value, 2, '.', ''); ?></td>
        <?php } ?>
      </tr>
      <tr>
        <td>Dimension 3</td>
        <?php foreach ($FINAL_INDICATOR_WD as $value) { ?>
          <td><?php echo number_format($value, 2, '.', ''); ?></td>
        <?php } ?>
      </tr>
      <tr>
        <td>Dimension 4</td>
        <?php foreach ($FINAL_INDICATOR_WH as $value) { ?>
          <td><?php echo number_format($value, 2, '.', ''); ?></td>
        <?php } ?>
      </tr>
      <tr>
        <td>Dimension 5</td>
        <?php foreach ($FINAL_INDICATOR_WG as $value) { ?>
          <td><?php echo number_format($value, 2, '.', ''); ?></td>
        <?php } ?>
      </tr>
      <!--  -->
      <tr>
        <td>Overall WSI</td>
        <?php foreach ($overallWSI as $value) { ?>
          <td><?php echo number_format($value, 2, '.', ''); ?></td>
        <?php } ?>
      </tr>
      <!--  -->
      <tr>
        <td>Interpretation</td>
        <?php foreach ($overallWSI_TEXT as $value) { ?>
          <td class="text-center"><?php echo $value; ?></td>
        <?php } ?>
      </tr>
    </tbody>
  </table>
</div>
<div class="container" style="margin-top:20px; margin-bottom:20px">
  <div class="row justify-content-center">
    <div class="col-5 order-1 text-center">
      <?php $color = interpretationColor($averageOverall) ?>
      <div class="bd-callout bd-callout-<?php echo $color; ?>">
        <h4 class="text-left">Average</h4>
        <a style="font-size:72px;"><span class="badge badge-<?php echo $color; ?>"><strong><?php echo number_format($averageOverall, 2, '.', ''); ?></strong></span></a>
        <h2><?php echo $averageOverall_TEXT; ?></h2>
      </div>
    </div>
    <div class="col-7 order-1">
      <div class="bd-callout bd-callout-secondary">
        <h5>Description</h5>
        The river basin is water secure with some dimensional aspect and insecure with the others. It faces less water-related issues. The basin has institutional management and has some plans to tackle future water challenges.
      </div>
    </div>
  </div>
</div>

<div class="container" style="margin-top:20px; margin-bottom:40px">
  <div class="row justify-content-center">
    <div style="width:90%;">
      <canvas id="canvas"></canvas>
    </div>
  </div>
</div>

<table class="table" style="margin-top:20px;">
  <thead class="thead-dark">
    <tr class="text-center">
      <th scope="col">Score</th>
      <th scope="col">Interpretation</th>
      <th scope="col">Description</th>
    </tr>
  </thead>
  <tbody>
    <tr class="text-center">
      <td style="white-space: nowrap;" class="table-danger"><strong>&#60; 1.5</strong></td>
      <td style="white-space: nowrap;" class="table-danger">Poor water Security</td>
      <td class="text-left">The river basin is highly insecure from the dimensional perspective. It faces several water-related issues. Furthermore, there is an essential requirement of proper institutional management and preparation for future water challenges</td>
    </tr>
    <tr class="text-center">
      <td style="white-space: nowrap;" class="table-warning"><strong>1.5-2.5</strong></td>
      <td style="white-space: nowrap;" class="table-warning">Low water Security</td>
      <td class="text-left">The river basin is water insecure from the dimensional perspective. It faces some water-related issues. The basin needs some improvement in the institutional management and preparation for future water challenges.</td>
    </tr>
    <tr class="text-center">
      <td style="white-space: nowrap;" class="table-success"><strong>2.5-3.5</strong></td>
      <td style="white-space: nowrap;" class="table-success">Good water Security</td>
      <td class="text-left">The river basin is water secure with some dimensional aspect and insecure with the others. It faces less water-related issues. The basin has institutional management and has some plans to tackle future water challenges.</td>
    </tr>
    <tr class="text-center">
      <td style="white-space: nowrap;" class="table-info"><strong>3.5-4.5</strong></td>
      <td style="white-space: nowrap;" class="table-info">Very Good water Security</td>
      <td class="text-left">The river basin is quite water secured from most of the dimensional aspect. It faces very less to none water-related issues. The basin has proper institutional management and good plans to tackle anticipated future water challenges.</td>
    </tr>
    <tr class="text-center">
      <td style="white-space: nowrap;" class="table-primary"><strong>> 4.5</strong></td>
      <td style="white-space: nowrap;" class="table-primary">Ideal water Security</td>
      <td class="text-left">The river basin is highly secure from all dimensional perspective. It has no water-related issues. The basin has excellent institutional management and it is fully prepared to tackle the anticipated future water challenges.</td>
    </tr>
  </tbody>
</table>
<!-- ------------------------------------------------------------------- -->
<script>
  var wa = <?php echo json_encode($FINAL_INDICATOR_WA); ?>;
  var wp = <?php echo json_encode($FINAL_INDICATOR_WP); ?>;
  var wd = <?php echo json_encode($FINAL_INDICATOR_WD); ?>;
  var wh = <?php echo json_encode($FINAL_INDICATOR_WH); ?>;
  var wg = <?php echo json_encode($FINAL_INDICATOR_WG); ?>;

  var dataWa = [];
  var dataWp = [];
  var dataWd = [];
  var dataWh = [];
  var dataWg = [];

  var labels = [];
  for (const year in wa) {
    labels.push(year)
    dataWa.push(wa[year])
    dataWp.push(wp[year])
    dataWd.push(wd[year])
    dataWh.push(wh[year])
    dataWg.push(wg[year])
  }

  var config = {
    type: 'line',
    data: {
      labels: labels,
      datasets: [{
        label: 'Dimension 1',
        backgroundColor: window.chartColors.red,
        borderColor: window.chartColors.red,
        data: dataWa,
        fill: false,
      }, {
        label: 'Dimension 2',
        fill: false,
        backgroundColor: window.chartColors.blue,
        borderColor: window.chartColors.blue,
        data: dataWp,
      }, {
        label: 'Dimension 3',
        fill: false,
        backgroundColor: window.chartColors.green,
        borderColor: window.chartColors.green,
        data: dataWd,
      }, {
        label: 'Dimension 4',
        fill: false,
        backgroundColor: window.chartColors.yellow,
        borderColor: window.chartColors.yellow,
        data: dataWh,
      }, {
        label: 'Dimension 5',
        fill: false,
        backgroundColor: window.chartColors.orange,
        borderColor: window.chartColors.orange,
        data: dataWg,
      }]
    },
    options: {
      responsive: true,
      title: {
        display: true,
        text: 'AGGREGATION',
        fontSize: 20
      },
      tooltips: {
        mode: 'point',
        intersect: true,
        callbacks: {
          label: function(t, d) {
            var xLabel = d.datasets[t.datasetIndex].label;
            var yLabel = t.yLabel;
            return xLabel + ": " + (Math.round(yLabel * 100) / 100).toFixed(3);
          }
        }
      },
      scales: {
        pointLabels: {
          fontSize: 16,
        },
        xAxes: [{
          scaleLabel: {
            fontSize: 16,
            display: true,
            labelString: 'YEAR'
          }
        }],
        yAxes: [{
          scaleLabel: {
            fontSize: 16,
            display: true,
            labelString: 'INDEX'
          }
        }]
      }
    }
  };

  window.onload = function() {
    var ctx = document.getElementById('canvas').getContext('2d');
    window.myLine = new Chart(ctx, config);
  };

  var colorNames = Object.keys(window.chartColors);
</script>