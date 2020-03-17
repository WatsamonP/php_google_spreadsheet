<?php
require __DIR__ . "./../config.php";
require __DIR__ . "./../php/brief.php";
require __DIR__ . "./../php/dimension_one.php";
require_once __DIR__ . "./../constants/word.php";
?>
<!--  -->
<nav class="navbar navbar-light bg-light">
  <a class="navbar-brand" href="#" style="font-size:30px">
    <div id="sidebarCollapse">
      <i id="sidebarIcon" class="fas fa-caret-square-left"></i>
      DIMENSION 1
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
      <?php
      foreach ($WA_SET as $sKey => $SET) {
        $ACTIVE_TAB = $sKey == 'WA1' ? 'active' : '';
      ?>
        <li class="nav-item">
          <a <?php echo "class='nav-link " . $ACTIVE_TAB . "'" ?> <?php echo "href='#" . $sKey . "'" ?> role="tab" <?php echo "aria-controls='" . $sKey . "'" ?> aria-selected="false"><?php echo $sKey ?> </a>
        </li>
      <?php
      } ?>
      <li class="nav-item">
        <a class='nav-link' href='#wa_final' role="tab" aria-controls='final' aria-selected="false">FINAL</a>
      </li>
    </ul>
    <!-- END TAB -->
  </div>
  <div class="card-body">
    <div class="tab-content">
      <div class="tab-pane" id="wa_final" role="tabpanel" aria-labelledby="history-tab">
        <?php include __DIR__ . "./../templates/WA/wa_final.php" ?>
      </div>
      <?php foreach ($WA_SET as $sKey => $SET) {
        $ACTIVE_TAB = $sKey == 'WA1' ? 'active' : '';
      ?>
        <div <?php echo "class='tab-pane " . $ACTIVE_TAB . "'" ?> <?php echo "id='" . $sKey . "'" ?> role="tabpanel" aria-labelledby="history-tab">
          <?php
          foreach ($SET as $gKey => $GROUP) {
          ?> <H4 style="margin-bottom:20px"><?php echo $gKey ?>
              : <?php echo $GROUP['name'] ?></H4>
            <!-- START SPECIAL TABLE -->
            <?php
            if ($gKey == "WA11") {
              include __DIR__ . "./../templates/WA/wa_11_annual_rainfall_table.php";
            } ?>
            <!-- START END TABLE -->

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

            <!-- START SCORE TABLE -->
            <?php
            if ($gKey == "WA11") {
              include __DIR__ . "./../templates/WA/wa_11_score_table.php";
            } else if ($gKey == "WA12") {
              $WA_TB_TEMP = $WA12_TB;
              include __DIR__ . "./../templates/WA/wa_score_table.php";
              ////////////////////////////////////////////
            } else if ($gKey == "WA21_B") {
              include  __DIR__ . "./../templates/utils/hr.html";
              $WA_TB_TEMP = $WA31_TB;
              include __DIR__ . "./../templates/WA/wa_score_table.php";
              ////////////////////////////////////////////
            } else if ($gKey == "WA31_B") {
              include  __DIR__ . "./../templates/utils/hr.html";
              $WA_TB_TEMP = $WA31_TB;
              include __DIR__ . "./../templates/WA/wa_score_table.php";
              ////////////////////////////////////////////
            } else if ($gKey == "WA41_B") {
              include  __DIR__ . "./../templates/utils/hr.html";
              $WA_TB_TEMP = $WA41_TB;
              include __DIR__ . "./../templates/WA/wa_score_table.php";
              ////////////////////////////////////////////
            } else if ($gKey == "WA51") {
              $WA_TB_TEMP = $WA51_TB;
              include __DIR__ . "./../templates/WA/wa_score_table.php";
              ////////////////////////////////////////////
            } else if ($gKey == "WA61") {
              $WA_TB_TEMP = $WA61_TB;
              include __DIR__ . "./../templates/WA/wa_score_table.php";
              include  __DIR__ . "./../templates/utils/hr.html";
              // SPECIFIC TABLE FOR WA62
              include __DIR__ . "./../templates/WA/wa_62_score_table.php";
              ////////////////////////////////////////////
            } else if (strpos($gKey, '_A')) {
              echo "";
            } else {
              echo "<p>Data not Found</p>";
            } ?>
            <!--  END SCORE TABLE -->
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
                          if ($sKey == "WA1") {
                            include __DIR__ . "./../templates/WA/weight_table/wa1_weight_table.php";
                          } else if ($sKey == "WA2") {
                            include __DIR__ . "./../templates/WA/weight_table/wa2_weight_table.php";
                          } else if ($sKey == "WA3") {
                            include __DIR__ . "./../templates/WA/weight_table/wa3_weight_table.php";
                          } else if ($sKey == "WA4") {
                            include __DIR__ . "./../templates/WA/weight_table/wa4_weight_table.php";
                          } else if ($sKey == "WA5") {
                            include __DIR__ . "./../templates/WA/weight_table/wa5_weight_table.php";
                          } else if ($sKey == "WA6") {
                            include __DIR__ . "./../templates/WA/weight_table/wa6_weight_table.php";
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
                              if (isset($FINAL_SCORE_WA[$sKey])) {
                                echo "id='wa-score-table'";
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
                            if (isset($FINAL_SCORE_WA[$sKey]))
                              foreach ($FINAL_SCORE_WA[$sKey] as  $year => $score) { ?>
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

    var editaleID = "#editable_table_WA11, #editable_table_WA12, " +
      "#editable_table_WA21_A, #editable_table_WA21_B, " +
      "#editable_table_WA31_A, #editable_table_WA31_B, " +
      "#editable_table_WA41_A, #editable_table_WA41_B, " +
      "#editable_table_WA51, " +
      "#editable_table_WA61"

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
        // console.log('onDraw()');
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
            $('#loading').show();
            setTimeout(function() {
              location.reload()
            }, 100);
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
            $("#wa-score-table").load(location.href + " #wa-score-table");
          },
        })
      },
      onFail: function(jqXHR, textStatus, errorThrown) {
        console.log('onFail(jqXHR, textStatus, errorThrown)');
        // console.log(jqXHR);
        // console.log(textStatus);
        // console.log(errorThrown);
      },
      onAlways: function() {
        // console.log('onAlways()');
      },
      onAjax: function(action, serialize) {
        console.log('onAjax(action, serialize)');
        // console.log(action);
        // console.log(serialize);
      }
    });
  });
</script>