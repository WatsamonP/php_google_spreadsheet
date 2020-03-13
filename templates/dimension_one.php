<?php
require_once "config.php";
require_once "php/brief.php";
require_once "php/dimension_one.php";
require_once "constants/word.php";
?>
<div id="alertNumeric" style="display: none" class="alert alert-danger" role="alert">
  Please Enter Only <a href="#" class="alert-link">Numeric Characters</a> | This value will not be saved.
</div>
<div class="card">
  <div class="card-header">
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
        <a class='nav-link' href='#final' role="tab" aria-controls='final' aria-selected="false">Final</a>
      </li>
    </ul>
  </div>
  <div class="card-body">
    <div class="tab-content">
      <?php foreach ($WA_SET as $sKey => $SET) {
        $ACTIVE_TAB = $sKey == 'WA1' ? 'active' : '';
      ?>
        <div <?php echo "class='tab-pane " . $ACTIVE_TAB . "'" ?> <?php echo "id='" . $sKey . "'" ?> role="tabpanel" aria-labelledby="history-tab">
          <?php
          foreach ($SET as $gKey => $GROUP) {
          ?> <H4 style="margin-bottom:20px"><?php echo $gKey ?></H4>
            <?php
            if ($gKey == "WA11") {
              include "templates/WA/wa_11_annual_rainfall_table.php";
            } ?>
            <!-- START TABLE -->
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
            <!-- END TABLE -->
            <?php
            if ($gKey == "WA11") {
              include "templates/WA/wa_11_score_table.php";
            } ?>
            <?php
            if ($gKey == "WA12") {
              include "templates/WA/wa_12_socre_table.php";
            } ?>
            <div class="row" style="margin-top: 30px; margin-bottom: 30px">
              <div class="col">
                <hr>
              </div>
              <div class="col-auto">. . .</div>
              <div class="col">
                <hr>
              </div>
            </div>
          <?php }
          ?>
          <?php include "templates/weight_table.php" ?>
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

    $('#editable_table_WA11, #editable_table_WA12').Tabledit({
      url: 'actions/act_dimension_one.php',
      columns: {
        identifier: [0, 'id'],
        editable: editableData,
      },
      hideIdentifier: true,
      restoreButton: false,
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
          url: 'actions/action_key_post.php',
          type: 'post',
          data: data,
          success: function(response) {
            // console.log('SUCCESS')
            $('#loading').show();
            setTimeout(function() {
              location.reload()
            }, 100);
          }
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