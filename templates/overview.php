<?php
require __DIR__ . "./../config.php";
require __DIR__ . "./../php/brief.php";
require __DIR__ . "./../php/overview.php";
require_once __DIR__ . "./../constants/word.php";
require_once __DIR__ . "./../constants/gid.php";
require_once __DIR__ . "./../constants/keys.php";

require_once __DIR__ . "./../php/fetch/fetchWX.php";
$responseWA = $service->spreadsheets_values->get($spreadsheetId, $WA_SHEET);
$WA_SET = getWxData($responseWA->getValues(), $ratio)["SET"];
$responseWP = $service->spreadsheets_values->get($spreadsheetId, $WP_SHEET);
$WP_SET = getWxData($responseWP->getValues(), $ratio)["SET"];
$responseWD = $service->spreadsheets_values->get($spreadsheetId, $WD_SHEET);
$WD_SET = getWxData($responseWD->getValues(), $ratio)["SET"];
$responseWH = $service->spreadsheets_values->get($spreadsheetId, $WH_SHEET);
$WH_SET = getWxData($responseWH->getValues(), $ratio)["SET"];
$responseWG = $service->spreadsheets_values->get($spreadsheetId, $WG_SHEET);
$WG_SET = getWxData($responseWG->getValues(), $ratio)["SET"];

?>
<!--  -->
<nav class="navbar navbar-light bg-light">
  <a class="navbar-brand" href="#" style="font-size:30px">
    <div id="sidebarCollapse">
      <i id="sidebarIcon" class="fas fa-caret-square-left"></i>
      <strong><?php echo $title[2] ?></strong> : OVERVIEW
    </div>
  </a>
  <form class="form-inline">
    <button id="editOverview" type="button" class="btn btn-warning" data-toggle="modal" data-target="#editBriefModal">
      <i class="fas fa-edit"></i> Edit
    </button>
    &nbsp;&nbsp;
    <button id="addProvince" type="button" class="btn btn-success" data-toggle="modal" data-target="#addProvinceModal">
      <i class="fas fa-globe-asia"></i> Add
    </button>
  </form>
</nav>
<!-- --------------------------------------------------- -->
<div class="modal fade" id="addProvinceModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-globe-asia"></i> Add New Province</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">Province Name</span>
          </div>
          <input id="addNewProvinceInput" type="text" class="form-control" placeholder="Name" aria-label="Name" aria-describedby="basic-addon1">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button id="addNewProvinceBtn" type="button" class="btn btn-success"><i class="fas fa-globe-asia"></i> Add</button>
      </div>
    </div>
  </div>
</div>
<!-- ------------------------------------------------- -->
<div class="modal fade bd-example-modal-sm" id="processing_times" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
      </div>
      <div class="modal-body text-center">
        <H1 id="processStatus" class="loading_dot" style="color:rgb(14, 128, 194);">Processing WA Sheet</H1>
      </div>
    </div>
  </div>
</div>
<!--  -->
<div style="overflow-y:auto;" class="modal fade" id="confirmDialog" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirmation</h5>
      </div>
      <div class="modal-body">
        <a id="confirmEditBriefContent"></a>
      </div>
      <div class="modal-footer">
        <button id="confirmEditBrief" type="button" class="btn btn-primary">Save changes</button>
        <button id="cancelConfirmEditBrief" type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="editBriefModal" tabindex="-1" role="dialog" aria-labelledby="editBriefModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editBriefModalLabel">Edit Information</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="edit_brief" onsubmit="submitForm(this.title.value, this.year_start.value, this.year_end.value, this.province_unit.value, this.basin_unit.value, <?php echo $year_start[2] ?>, <?php echo $year_end[2] ?>)" method="post">
        <div class="modal-body">
          <!--  -->
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" style="width: 180px"><?php echo $title[1] ?></span>
            </div>
            <input name="title" value="<?php echo $title[2] ?>" type="text" class="form-control">
          </div>
          <!--  -->
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" style="width: 180px"><?php echo $year_start[1] ?></span>
            </div>
            <input id="year_start" name="year_start" value="<?php echo $year_start[2] ?>" type="text" class="form-control">
            <input id="year_end" name="year_end" value="<?php echo $year_end[2] ?>" type="text" class="form-control">
          </div>
          <!--  -->
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" style="width: 180px"><?php echo $province_unit[1] ?></span>
            </div>
            <input name="province_unit" value="<?php echo $province_unit[2] ?>" type="text" class="form-control">
          </div>
          <!--  -->
          <div class="input-group mb-3">
            <div class="input-group-prepend">
              <span class="input-group-text" style="width: 180px"><?php echo $basin_unit[1] ?></span>
            </div>
            <input name="basin_unit" value="<?php echo $basin_unit[2] ?>" type="text" class="form-control">
          </div>
          <!--  -->

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <input type="submit" class="btn btn-primary">
        </div>
      </form>
    </div>
  </div>
</div>
<!-- START TABLE -->
<div class='table-responsive' style="margin-top:30px">
  <table id='editable_table' class='table table-hover'>
    <thead class='thead-dark'>
      <tr>
        <th style='width: 5%' scope='col' class='text-center'>#</th>
        <th style='display: none' scope='col'><?php echo $provincesHead[0] ?></th>
        <th style='width: 25%' scope='col'><?php echo $provincesHead[1] ?></th>
        <th style='width: 20%' scope='col' class='text-center'><?php echo $provincesHead[2] . " (" . toSup($province_unit[2]) . ")"  ?></th>
        <th style='width: 20%' scope='col' class='text-center'><?php echo $provincesHead[3] . " (" . toSup($basin_unit[2]) . ")" ?></th>
      </tr>
    </thead>

    <tbody id="editable_tableBody">
      <?php
      foreach ($provincesData as $index => $province) {
        $key = $index + 1;
      ?>
        <tr>
          <th scope='row' class="text-center"><?php echo $key ?></th>
          <td style='display: none'><?php echo $province['id'] ?></td>
          <td><?php echo $province['Name of the Province'] ?></td>
          <td class='text-center'><?php echo number_format($province[$string_provinceArea], 2, '.', '') ?></td>
          <td class='text-center'><?php echo number_format($province[$string_basinArea], 2, '.', '') ?></td>
        </tr>
      <?php }; ?>
    </tbody>
  </table>
  <table class="table">
    <thead class='thead-dark'>
      <tr>
        <th style='width: 5%'></th>
        <th style='width: 23%' class='text-left'><?php echo $string_BasinArea ?><strong></th>
        <th style='width: 20%'></th>
        <th style='width: 30%' class='text-center'><strong><?php echo $sumBasinArea ?></strong></th>
      </tr>
    </thead>
  </table>
</div>
<!-- -------------------------------------------------------------------------------------- -->
<script type="text/javascript">
  $('#btn_insert_row').click(function() {
    var table = $('#editable_table');
    var body = $('#editable_tableBody');
    var nextId = body.find('tr').length + 1;
    table.append($('<tr><td>' + nextId + '</td><td>Sue</td></tr>'));
    table.data('Tabledit').reload();
  });

  $('#cancelConfirmEditBrief').click(function() {
    $('#confirmDialog').modal('hide');
  });

  document.getElementById("edit_brief").addEventListener("submit", function(event) {
    event.preventDefault();
  });

  function submitForm(title, year_start, year_end, province_unit, basin_unit, prev_year_start, prev_year_end) {
    var headerID = ['dimen', 'group', 'subgroup', 'key', 'id', 'province', 'unit'];
    var headerSI = ['dimen', 'group', 'key', 'id', 'unit'];
    var headerRD = ['dimen', 'group', 'key', 'id', 'type', 'name', 'length', 'unit'];

    var insertBefortStart = [];
    var removeBefortStart = [];
    var insertAfterEnd = [];
    var removeAfterEnd = [];
    var insertStartMessage = ""
    var insertEndMessage = ""
    var removeStartMessage = "";
    var removeEndMessage = "";

    for (var i = year_start; i <= year_end; i++) {
      headerID.push(parseInt(i));
      headerSI.push(parseInt(i));
      headerRD.push(parseInt(i));
    }
    //////////////////////////////////////////////
    // e.g., first we have [2005, 2015]
    if (year_start > prev_year_start) {
      // [if] year_start = 2007 then we have to remove columns [2005, 2006] on every sheet
      for (var i = prev_year_start; i < year_start; i++) {
        removeBefortStart.push(parseInt(i))
        removeStartMessage += parseInt(i) + ", "
      }
    } else if (year_start < prev_year_start) {
      // [if] year_start = 2003 then we have to add more columns [2003, 2004] on every sheet
      for (var i = year_start; i < prev_year_start; i++) {
        insertBefortStart.push(parseInt(i))
        insertStartMessage += parseInt(i) + ", "
      }
    }
    ///////////////////////////////////
    if (year_end > prev_year_end) {
      // [if] year_end = 2017 then we have to add more columns [2016, 2017] on every sheet
      for (var i = prev_year_end; i < year_end; i++) {
        insertAfterEnd.push(parseInt(i) + 1)
        insertEndMessage += (parseInt(i) + 1) + ", "
      }
    } else if (year_end < prev_year_end) {
      // [if] year_end = 2013 then we have to remove columns [2014, 2015] on every sheet
      for (var i = year_end; i < prev_year_end; i++) {
        removeAfterEnd.push(parseInt(i) + 1)
        removeEndMessage += (parseInt(i) + 1) + ", "
      }
    }

    var message = ""
    var addSign = "";
    var delSign = "";
    if (removeStartMessage !== "" || removeEndMessage !== "") {
      delSign = "<br/> &emsp;&emsp;&emsp;<i class='fas fa-minus-circle'></i> "
      removeEndMessage += " will be deleted"
    }
    if (insertStartMessage !== "" || insertEndMessage !== "") {
      addSign = "<br/> &emsp;&emsp;&emsp;<i class='fas fa-plus-circle'></i> "
      insertEndMessage += " will be inserted"
    }
    if ((year_start == prev_year_start) && (year_end == prev_year_end)) {

    } else {
      message = "<a class='text-danger'><i class='fas fa-exclamation-triangle'></i> There might exist a risk that the service failed to handle this task and you may lose raw data</a>" +
        "<br/><a class='text-primary'><i class='fas fa-lightbulb'></i><strong> Recommendation</strong>. You should backup a worksheet before continuing this task</a>" +
        "<br/><hr/><br/>&emsp; The Year Range will change " +
        "FROM <strong>[" + prev_year_start + ", " + prev_year_end + "]</strong>" +
        "<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;" +
        "TO <strong>[" + headerID[7] + ", " + headerID[headerID.length - 1] + "]</strong><br/>" +
        addSign + insertStartMessage + insertEndMessage +
        delSign + removeStartMessage + removeEndMessage +
        "<br/><hr/><a class='text-danger'><i class='fas fa-exclamation-triangle'></i> Note that</a>" +
        "<li> You cannot <strong>RESTORE</strong> the data that going to be deleted</li>" +
        "<li> When you delete data of any particular year, this should be fine</li>" +
        "<li> However, when you insert year's data don't forget to fill those data, it will be treated like 'zero' for calculations</li>" +
        "<li> Actually, they are missing values and they might tell you the wrong information</li>";
    }
    // INSERT NEW COLS -> CLEAR HEADER ROW -> INSERT NEW HEADER
    // [2005, 2015]
    // new = [2003, 2017] -> INSERT + APPEND(Nothing to do)
    // new = [2007, 2013] -> REMOVE_FISRT_TWO + REMOVE_LAST_TWO 
    /////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////
    $('#confirmEditBriefContent').html(message);
    $('#editBriefModal').modal('hide');
    $('#confirmDialog').modal('show');
    $('#confirmEditBrief').click(function() {
      if ((year_start == prev_year_start) && (year_end == prev_year_end)) {
        updateOverview();
        $('#confirmDialog').modal('hide');
      } else {
        $('#confirmDialog').modal('hide');
        $('#processing_times').modal('show');

        function doWP(index) {
          setTimeout(function() {
            wpValue.forEach(function(element, index) {
              setTimeout(function() {
                AppendNewProvince(element, <?php echo json_encode($WP_SHEET); ?>)
              }, 1200 * (index + 1));
              if (index == wpValue.length - 1)
                doWD(index);
            });
          }, 1200 * (index + 1));
        }

        StartTask(startIndex = 7, headerID, SHEET_GID = <?php echo json_encode($WA_SHEET_GID); ?>, SHEET = <?php echo json_encode($WA_SHEET); ?> + "!1:1", 'confirm_wa_sheet');
        setTimeout(function() {
          StartTask(startIndex = 7, headerID, SHEET_GID = <?php echo json_encode($WP_SHEET_GID); ?>, SHEET = <?php echo json_encode($WP_SHEET); ?> + "!1:1", 'confirm_wp_sheet');
        }, 2000);
        setTimeout(function() {
          StartTask(startIndex = 7, headerID, SHEET_GID = <?php echo json_encode($WD_SHEET_GID); ?>, SHEET = <?php echo json_encode($WD_SHEET); ?> + "!1:1", 'confirm_wd_sheet');
        }, 3200);
        setTimeout(function() {
          StartTask(startIndex = 7, headerID, SHEET_GID = <?php echo json_encode($WH_SHEET_GID); ?>, SHEET = <?php echo json_encode($WH_SHEET); ?> + "!1:1", 'confirm_wh_sheet');
        }, 4400);
        setTimeout(function() {
          StartTask(startIndex = 7, headerID, SHEET_GID = <?php echo json_encode($WG_SHEET_GID); ?>, SHEET = <?php echo json_encode($WG_SHEET); ?> + "!1:1", 'confirm_wg_sheet');
        }, 5600);
        setTimeout(function() {
          StartTask(startIndex = 5, headerSI, SHEET_GID = <?php echo json_encode($SPECIFIC_INPUT_YEARS_SHEET_GID); ?>, SHEET = <?php echo json_encode($SPECIFIC_INPUT_YEARS_SHEET); ?> + "!1:1", 'confirm_sta_val');
        }, 6800);
        setTimeout(function() {
          StartTask(startIndex = 8, headerRD, SHEET_GID = <?php echo json_encode($RIVER_DAM_LIST_GID); ?>, SHEET = <?php echo json_encode($RIVER_DAM_LIST_SHEET); ?> + "!1:1", 'confirm_river_dam');
        }, 8000);
      }
    })

    function StartTask(startIndex, HEADER, SHEET_GID, SHEET, DIV_ID) {
      if (removeAfterEnd.length !== 0) {
        deleteYearAfter(startIndex, HEADER, SHEET_GID, SHEET, DIV_ID);
      } else if (removeBefortStart.length !== 0) {
        deleteYearBefore(startIndex, HEADER, SHEET_GID, SHEET, DIV_ID);
      } else if (insertBefortStart.length !== 0) {
        insertYearBefore(startIndex, HEADER, SHEET_GID, SHEET, DIV_ID);
      } else if (insertAfterEnd.length !== 0) {
        clearHeader(HEADER, SHEET_GID, SHEET, DIV_ID);
      } else {
        console.log('Nothing update about year range')
        updateOverview();
      }
    }

    //////////////////////////////////////////////////////////////////////////////////////////
    // [FIRST PRIORITY]
    function deleteYearAfter(startIndex, HEADER, SHEET_GID, SHEET, DIV_ID) {
      console.log('PROCESSING REMOVE COLS AFTER END YEAR')
      var prevLastIndex = (prev_year_end - prev_year_start) + startIndex + 1;
      var startRemoveIndex = prevLastIndex - removeAfterEnd.length;
      $.ajax({
        url: "actions/batch_delete_cols.php",
        type: 'post',
        data: {
          'id': "DELETE_COLS",
          'sheetId': SHEET_GID,
          'startIndex': startRemoveIndex,
          'endIndex': prevLastIndex
        },
        success: function(response) {
          console.log("SUCCESS REMOVE COLS AFTER END YEAR")
          if (removeBefortStart.length !== 0) {
            deleteYearBefore(startIndex, HEADER, SHEET_GID, SHEET, DIV_ID);
          } else if (insertBefortStart.length !== 0) {
            insertYearBefore(startIndex, HEADER, SHEET_GID, SHEET, DIV_ID);
          } else {
            clearHeader(HEADER, SHEET_GID, SHEET, DIV_ID);
          }
        }
      })
    }
    ///////////////////////////////////////////////////////
    function deleteYearBefore(startIndex, HEADER, SHEET_GID, SHEET, DIV_ID) {
      console.log('PROCESSING REMOVE COLS BEFORE START YEAR')
      $.ajax({
        url: "actions/batch_delete_cols.php",
        type: 'post',
        data: {
          'id': "DELETE_COLS",
          'sheetId': SHEET_GID,
          'startIndex': startIndex,
          'endIndex': startIndex + removeBefortStart.length
        },
        success: function(response) {
          console.log("SUCCESS REMOVE COLS BEFORE START YEAR")
          clearHeader(HEADER, SHEET_GID, SHEET, DIV_ID);
        }
      })
    }
    /////////////////////////////////////////////////////////////////
    function insertYearBefore(startIndex, HEADER, SHEET_GID, SHEET, DIV_ID) {
      console.log('PROCESSING INSERT COLS BEFORE START YEAR')
      $.ajax({
        url: "actions/batch_insert_cols.php",
        type: 'post',
        data: {
          'id': "INSERT_COLS",
          'sheetId': SHEET_GID,
          'startIndex': startIndex,
          'endIndex': startIndex + insertBefortStart.length
        },
        success: function(response) {
          console.log("SUCCESS INSERT COLS BEFORE START YEAR")
          clearHeader(HEADER, SHEET_GID, SHEET, DIV_ID);
        }
      })
    }
    ////////////////////////////////////////////////////
    function clearHeader(HEADER, SHEET_GID, SHEET, DIV_ID) {
      console.log('[2] PROCESSING CLEAR HEADER')
      $.ajax({
        url: "actions/batch_clear_rows.php",
        type: 'post',
        data: {
          'id': "CLEAR_HEADER",
          'sheetId': SHEET_GID,
          'startRowIndex': 0,
          'endRowIndex': 1
        },
        success: function(response) {
          console.log("SUCCESS CLEAR HEADER")
          insertHeader(HEADER, SHEET, DIV_ID);
        }
      })
    }

    function insertHeader(HEADER, SHEET, DIV_ID) {
      console.log('[3] PROCESSING INSERT NEW HEADER')
      $.ajax({
        url: "actions/act_insert_value_row.php",
        type: 'post',
        data: {
          'id': "INSERT_ROW",
          'range': SHEET,
          'data': HEADER,
        },
        success: function(response) {
          console.log("SUCCESS INSERT NEW HEADER")
          if (DIV_ID == 'confirm_wa_sheet') {
            $('#processStatus').html('Processing WP Sheet');
          } else if (DIV_ID == 'confirm_wp_sheet') {
            $('#processStatus').html('Processing WD Sheet');
          } else if (DIV_ID == 'confirm_wd_sheet') {
            $('#processStatus').html('Processing WH Sheet');
          } else if (DIV_ID == 'confirm_wh_sheet') {
            $('#processStatus').html('Processing WG Sheet');
          } else if (DIV_ID == 'confirm_wg_sheet') {
            $('#processStatus').html('Almost done');
          } else if (DIV_ID == 'confirm_sta_val') {
            console.log('confirm_sta_val');
          } else if (DIV_ID == 'confirm_river_dam') {
            updateOverview();
          }
        }
      })
    }
    //////////////////////////////////////////////////////////////////
    function updateOverview() {
      $.ajax({
        url: "actions/act_edit_brief.php",
        type: 'post',
        data: {
          'title': title,
          'year_start': parseInt(year_start),
          'year_end': parseInt(year_end),
          'province_unit': province_unit,
          'basin_unit': basin_unit
        },
        success: function(response) {
          $('#processing_times').modal('hide');
          console.log('SUCCESS')
          $('#loading').show();
          setTimeout(function() {
            location.reload()
          }, 1000);
        }
      });
    }
  }
  ///////////////////////////////////////////////////////////////////////////////
  $(document).ready(function() {
    $('#editable_table').Tabledit({
      url: 'actions/act_pre_overview.php',
      columns: {
        identifier: [1, 'id'],
        editable: [
          [2, 'provinceName'],
          [3, 'provinceArea'],
          [4, 'basinArea'],
        ],
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
        console.log('onDraw()');
      },
      onSuccess: function(data, textStatus, jqXHR) {
        console.log('onSuccess(data, textStatus, jqXHR)');
        console.log(textStatus);
        console.log(jqXHR);
        $.ajax({
          url: 'actions/act_edit_overview.php',
          type: 'post',
          data: data,
          success: function(response) {
            console.log('SUCCESS')
            $('#loading').show();
            setTimeout(function() {
              location.reload()
            }, 1000);
          }
        })
      },
      onFail: function(jqXHR, textStatus, errorThrown) {
        console.log('onFail(jqXHR, textStatus, errorThrown)');
        console.log(jqXHR);
        console.log(textStatus);
        console.log(errorThrown);
      },
      onAlways: function() {
        console.log('onAlways()');
      },
      onAjax: function(action, serialize) {
        console.log('onAjax(action, serialize)');
        console.log(action);
        console.log(serialize);
      }
    });
  });
  ////////////////////////////////////////////////////////////////////////////////
  $('#addNewProvinceBtn').click(function() {
    ///////////////////////////////////////
    // $province['id']
    var provinces = <?php echo json_encode($provincesData); ?>;
    var lastProvinceId = provinces[provinces.length - 1]['id']

    var newProvinceId = (parseInt(lastProvinceId.replace("P", '')) + 1)
    var newProvinceName = $('#addNewProvinceInput').val();
    var newDataProvinces = [newProvinceId, newProvinceName, 0, 0];

    var yearValue = [];
    var yearStart = <?php echo json_encode($year_start[2]); ?>;
    var yearEnd = <?php echo json_encode($year_end[2]); ?>;
    for (var i = yearStart; i <= yearEnd; i++) {
      yearValue.push(0);
    }

    function constructData(set) {
      var tempDimen = [];
      var tempGroup = [];
      var tempUnit = [];
      var tempKey = [];
      var value = [];
      for (var i = 0; i < Object.keys(set).length; i++) {
        for (subset in set[Object.keys(set)[i]]) {
          tempDimen.push(Object.keys(set)[i])
          tempGroup.push(subset)
          tempUnit.push(set[Object.keys(set)[i]][subset]['unit'])
          tempKey.push(set[Object.keys(set)[i]][subset]['key'])
        }
      }
      for (var i = 0; i < tempGroup.length; i++) {
        value[i] = [];
        value[i].push(tempDimen[i])
        value[i].push(tempGroup[i].replace(/_[A-Z]/g, ""));
        value[i].push(tempGroup[i])
        value[i].push(tempKey[i])
        value[i].push(tempGroup[i] + "_" + newProvinceId)
        value[i].push(newProvinceName)
        value[i].push(tempUnit[i])
        value[i].push(...yearValue)
      }
      return value
    }

    if (newProvinceName == "") {
      alert("Cannot be empty");
    } else {
      $('#addProvinceModal').modal('hide');
      $('#processing_times').modal('show');
      /////////////////////////////////////////////////////////////////////
      // WA SHEET
      var wa_set = <?php echo json_encode($WA_SET); ?>;
      var waValue = constructData(wa_set)
      // WP SHEET
      var wp_set = <?php echo json_encode($WP_SET); ?>;
      var wpValue = constructData(wp_set)
      // WD SHEET
      var wd_set = <?php echo json_encode($WD_SET); ?>;
      var wdValue = constructData(wd_set)
      // WH SHEET
      var wh_set = <?php echo json_encode($WH_SET); ?>;
      var whValue = constructData(wh_set);
      // WG SHEET
      var wg_set = <?php echo json_encode($WG_SET); ?>;
      var wgValue = constructData(wg_set);
      ////////////////////////////////////////////////////////
      function doWG(index) {
        setTimeout(function() {
          wgValue.forEach(function(element, index) {
            setTimeout(function() {
              AppendNewProvince(element, <?php echo json_encode($WG_SHEET); ?>)
            }, 1200 * (index + 1));
            if (index == wgValue.length - 1)
              AppendNewProvince(["P" + newProvinceId, newProvinceName, 0, 0], <?php echo json_encode($PROVINCES_SHEET); ?>);
          });
        }, 1200 * (index + 1));
      }

      function doWH(index) {
        setTimeout(function() {
          whValue.forEach(function(element, index) {
            setTimeout(function() {
              AppendNewProvince(element, <?php echo json_encode($WH_SHEET); ?>)
            }, 1200 * (index + 1));
            if (index == whValue.length - 1)
              doWG(index);
          });
        }, 1200 * (index + 1));
      }

      function doWD(index) {
        setTimeout(function() {
          wdValue.forEach(function(element, index) {
            setTimeout(function() {
              AppendNewProvince(element, <?php echo json_encode($WD_SHEET); ?>)
            }, 1200 * (index + 1));
            if (index == wdValue.length - 1)
              doWH(index);
          });
        }, 1200 * (index + 1));
      }

      function doWP(index) {
        setTimeout(function() {
          wpValue.forEach(function(element, index) {
            setTimeout(function() {
              AppendNewProvince(element, <?php echo json_encode($WP_SHEET); ?>)
            }, 1200 * (index + 1));
            if (index == wpValue.length - 1)
              doWD(index);
          });
        }, 1200 * (index + 1));
      }
      waValue.forEach(function(element, index) {
        setTimeout(function() {
          AppendNewProvince(element, <?php echo json_encode($WA_SHEET); ?>)
        }, 1200 * (index + 1));
        if (index == waValue.length - 1)
          doWP(index);
      });
    }

    function AppendNewProvince(newData, SHEET_NAME) {
      $.ajax({
        url: "actions/act_append.php",
        type: 'post',
        data: {
          'id': 'appendNewProvince',
          'data': newData,
          'sheet_id': SHEET_NAME,
        },
        success: function(response) {
          if (SHEET_NAME == 'WA') {
            $('#processStatus').html('Processing WP Sheet');
          } else if (SHEET_NAME == 'WP') {
            $('#processStatus').html('Processing WD Sheet');
          } else if (SHEET_NAME == 'WD') {
            $('#processStatus').html('Processing WH Sheet');
          } else if (SHEET_NAME == 'WH') {
            $('#processStatus').html('Processing WG Sheet');
          } else if (SHEET_NAME == 'WG') {
            $('#processStatus').html('Almost done');
          } else if (SHEET_NAME == 'Provinces') {
            $('#processing_times').modal('hide');
            $('#loading').show();
            setTimeout(function() {
              location.reload()
            }, 1000);
          }
        }
      });
    }
  })
  ////////////////////////////////////////////////////////////
</script>