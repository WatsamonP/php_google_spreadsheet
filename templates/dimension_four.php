<?php
require __DIR__ . "./../config.php";
require __DIR__ . "./../php/brief.php";
require __DIR__ . "./../php/dimension_four.php";
require_once __DIR__ . "./../constants/word.php";
?>
<!--  -->
<nav class="navbar navbar-light bg-light">
  <a class="navbar-brand" href="#" style="font-size:30px">
    <div id="sidebarCollapse">
      <i id="sidebarIcon" class="fas fa-caret-square-left"></i>
      DIMENSION 4
    </div>
  </a>
</nav>
<!-- NUMERTIC ALERT -->
<?php include __DIR__ . "./../templates/alert/numeric_alert.html" ?>

<!-- START -->
<div class="card">
  <div class="card-header">
    <!-- START TAB -->
    <ul class="nav nav-tabs card-header-tabs" id="bologna-list" role="tablist">
      <li class="nav-item">
        <a class='nav-link active' href='#WH1' role="tab" aria-controls='WH1' aria-selected="false">WH1</a>
      </li>
      <li class="nav-item">
        <a class='nav-link' href='#WH2' role="tab" aria-controls='WH2' aria-selected="false">WH2</a>
      </li>
      <li class="nav-item">
        <a class='nav-link' href='#WH3' role="tab" aria-controls='WH3' aria-selected="false">WH3</a>
      </li>
      <li class="nav-item">
        <a class='nav-link' href='#WH4' role="tab" aria-controls='WH4' aria-selected="false">WH4</a>
      </li>
      <li class="nav-item">
        <a class='nav-link' href='#WH5' role="tab" aria-controls='WH5' aria-selected="false">WH5</a>
      </li>
      <li class="nav-item">
        <a class='nav-link' href='#WH6' role="tab" aria-controls='WH6' aria-selected="false">WH6</a>
      </li>
      <li class="nav-item">
        <a class='nav-link' href='#WH7' role="tab" aria-controls='WH7' aria-selected="false">WH7</a>
      </li>
      <li class="nav-item">
        <a class='nav-link' href='#whfinal' role="tab" aria-controls='final' aria-selected="false">FINAL</a>
      </li>
    </ul>
    <!-- END TAB -->
  </div>
  <div class="card-body">
    <div class="tab-content">
      <div class="tab-pane active" id="WH1" role="tabpanel" aria-labelledby="history-tab">
        <?php include __DIR__ . "./../templates/WH/wh_11_table.php"; ?>
      </div>
      <div class="tab-pane" id="WH6" role="tabpanel" aria-labelledby="history-tab">
        <?php include __DIR__ . "./../templates/WH/wh_61_table.php"; ?>
      </div>
      <div class="tab-pane" id="whfinal" role="tabpanel" aria-labelledby="history-tab">
        <?php include __DIR__ . "./../templates/WH/wh_final.php"; ?>
      </div>
      <!--  -->
      <?php foreach ($WH_SET as $sKey => $SET) {
        $ACTIVE_TAB = $sKey == 'WH1' ? 'active' : '';
      ?>
        <div <?php echo "class='tab-pane " . $ACTIVE_TAB . "'" ?> <?php echo "id='" . $sKey . "'" ?> role="tabpanel" aria-labelledby="history-tab">
          <H3 style="margin-bottom:40px"><strong><?php echo "[ " . $sKey . " ] " . $WeightKeysData[$sKey]['name'] ?></strong></H3>
          <?php
          foreach ($SET as $gKey => $GROUP) {
          ?> <H4 style="margin-bottom:20px"><?php echo $gKey ?>
              : <?php echo $GROUP['name'] ?></H4>

            <!-- START INPUT TABLE [GREEN] -->
            <div class='table-responsive' style="margin-top:30px">
              <H5>
                <i class="fas fa-table"></i> <?php echo $GROUP['key'] ?> (<?php echo toSup($GROUP['unit']) ?>)
              </H5>
              <!--  -->
              <table <?php echo "id='editable_table_" . $gKey . "'" ?> class='table table-hover' style="display: block !important;">
                <thead class='thead-dark'>
                  <tr>
                    <th style='display: none' scope='col'></th>
                    <th style='width: 5%' scope='col' class='text-center'>#</th>
                    <th scope='col' class='text-center' style="white-space: nowrap;"><?php echo $string_provinceName ?></th>
                    <?php
                    foreach ($YEAR_RANGE as $year) {
                    ?>
                      <th class='text-center'><?php echo $year ?></th>
                    <?php }
                    ?>
                  </tr>
                </thead>
                <tbody id="editable_tableBody">
                  <?php
                  $index = 1;
                  foreach ($GROUP['data'] as $iKey => $item) {
                  ?>
                    <tr>
                      <td style='display: none'><?php echo $iKey ?></td>
                      <th class='text-center'><?php echo $index ?></th>
                      <td><?php echo $item['province'] ?></td>
                      <?php
                      foreach ($item['table'] as $val) {
                      ?>
                        <td class='text-right'><?php echo number_format($val, 2, '.', '') ?></th>
                        <?php }
                        ?>
                        </td>
                    </tr> <?php $index++;
                        } ?> </tbody>
              </table>
            </div>
            <!-- END INPUT TABLE [GREEN] -->

            <!-- START OPTIONAL TABLE -->
            <!--  END OPTIONAL TABLE -->
            <?php include  __DIR__ . "./../templates/utils/hr.html" ?>
          <?php }
          ?>
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
                          <?php
                          if ($sKey == "WH2") {
                            include __DIR__ . "./../templates/WH/weight_table/wh2_weight_table.php";
                          } else if ($sKey == "WH3") {
                            include __DIR__ . "./../templates/WH/weight_table/wh3_weight_table.php";
                          } else if ($sKey == "WH4") {
                            include __DIR__ . "./../templates/WH/weight_table/wh4_weight_table.php";
                          } else if ($sKey == "WH5") {
                            include __DIR__ . "./../templates/WH/weight_table/wh5_weight_table.php";
                          } else if ($sKey == "WH7") {
                            include __DIR__ . "./../templates/WH/weight_table/wh7_weight_table.php";
                          } else {
                            echo "<p>Data not Found</p>";
                          } ?>
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
                      <table <?php
                              if (isset($FINAL_SCORE_WH[$sKey])) {
                                echo "id='wh-" . $sKey . "-score-table'";
                              } ?> class='table table-hover' style="margin: 0 auto;">
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
                            if (isset($FINAL_SCORE_WH[$sKey]))
                              foreach ($FINAL_SCORE_WH[$sKey] as  $year => $score) { ?>
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
        </div>
      <?php
      } ?>
    </div>
  </div>
</div>

<script type="text/javascript">
  $('#bologna-list a').on('click', function(e) {
    e.preventDefault()
    $(this).tab('show')
  })

  $(document).ready(function() {
    var years = <?php echo json_encode($YEAR_RANGE, JSON_HEX_TAG); ?>;
    var year_start_col = 3;
    var editableData = [];

    Object.entries(years).forEach(([key, value], index) => {
      editableData[index] = [index + year_start_col, value]
    });

    var editaleID = "#editable_table_WH21_A, #editable_table_WH21_B, " +
      "#editable_table_WH31, #editable_table_WH32, " +
      "#editable_table_WH41, #editable_table_WH51, #editable_table_WH71 ";

    $(editaleID).Tabledit({
      url: 'actions/act_pre_dimension_one.php',
      columns: {
        identifier: [0, 'id'],
        editable: editableData,
      },
      hideIdentifier: true,
      restoreButton: false,
      deleteButton: false,
      buttons: {
        edit: {
          class: 'btn btn-default',
          html: '<i class="fas fa-edit"></i>',
          action: 'edit'
        },
        delete: {
          class: 'btn btn-default',
          html: '<i class="fas fa-trash"></i>',
          action: 'delete'
        },
        save: {
          class: 'btn btn-sm btn-success',
          html: 'Save'
        },
        confirm: {
          class: 'btn btn-sm btn-danger',
          html: 'Confirm'
        }
      },
      onDraw: function() {
        console.log('onDraw(D2)');
      },
      onSuccess: function(data, textStatus, jqXHR) {
        function isNumber(n) {
          return !isNaN(parseFloat(n)) && !isNaN(n - 0)
        }

        for (const [key, value] of Object.entries(data['data'][0])) {
          if (!isNumber(value)) {
            data['data'][0][key] = parseFloat(0);
            $("#alertNumeric").fadeTo(3000, 1000).slideUp(1000, function() {
              $("alertNumeric").slideUp(1000);
            });
            // $('#loading').show();
            // setTimeout(function() {
            //   location.reload()
            // }, 100);
            return false
          } else {
            data['data'][0][key] = parseFloat(value);
          }
        }
        $.ajax({
          url: 'actions/act_edit_table.php',
          type: 'post',
          data: data,
          success: function(response) {
            $('#loading').show()
            if (data.id.includes('WH2')) {
              $("#wh-WH2-score-table").load(location.href + " #wh-WH2-score-table", function() {
                $('#loading').hide()
              });
            } else if (data.id.includes('WH3')) {
              $("#wh-WH3-score-table").load(location.href + " #wh-WH3-score-table", function() {
                $('#loading').hide()
              });
            } else if (data.id.includes('WH4')) {
              $("#wh-WH4-score-table").load(location.href + " #wh-WH4-score-table", function() {
                $('#loading').hide()
              });
            } else if (data.id.includes('WH5')) {
              $("#wh-WH5-score-table").load(location.href + " #wh-WH5-score-table", function() {
                $('#loading').hide()
              });
            } else if (data.id.includes('WH7')) {
              $("#wh-WH7-score-table").load(location.href + " #wh-WH7-score-table", function() {
                $('#loading').hide()
              });
            } else {
              $('#loading').hide()
            }
          },
        })
      },
      onFail: function(jqXHR, textStatus, errorThrown) {
        console.log('onFail(jqXHR, textStatus, errorThrown)');
      },
      onAlways: function() {
        console.log('onAlways()');
      },
      onAjax: function(action, serialize) {
        console.log('onAjax(action, serialize)');
      }
    });
  });
</script>