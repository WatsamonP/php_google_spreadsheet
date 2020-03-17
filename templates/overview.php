<?php
require __DIR__ . "./../config.php";
require __DIR__ . "./../php/brief.php";
require __DIR__ . "./../php/overview.php";
require_once __DIR__ . "./../constants/word.php";
?>
<!--  -->
<nav class="navbar navbar-light bg-light">
  <a class="navbar-brand" href="#" style="font-size:30px">
    <div id="sidebarCollapse">
      <i id="sidebarIcon" class="fas fa-caret-square-left"></i>
      OVERVIEW
    </div>
  </a>
  <form class="form-inline">
    <button id="editOverview" type="button" class="btn btn-warning" data-toggle="modal" data-target="#editBriefModal">
      <i class="fas fa-edit"></i> Edit
    </button>
  </form>
</nav>
<!--  -->
<div class="modal fade bd-example-modal-sm" id="confirmDialog" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Confirmation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <a id="confirmEditBriefContent"></a>
      </div>
      <div class="modal-footer">
        <button id="confirmEditBrief" type="button" class="btn btn-primary">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
        <th scope='col' class='text-center'><?php echo $provincesHead[4] ?></th>
      </tr>
    </thead>

    <tbody id="editable_tableBody">
      <?php
      foreach ($provincesData as $index => $province) {
        $key = $index + 1;
      ?>
        <tr>
          <th scope='row'><?php echo $key ?></th>
          <td style='display: none'><?php echo $province['id'] ?></td>
          <td><?php echo $province['Name of the Province'] ?></td>
          <td class='text-center'><?php echo number_format($province[$string_provinceArea], 2, '.', '') ?></td>
          <td class='text-center'><?php echo number_format($province[$string_basinArea], 2, '.', '') ?></td>
          <td class='text-center'><?php echo number_format($ratio[$index], 2, '.', '') ?></td>
        </tr>
      <?php }; ?>
    </tbody>
  </table>
  <table class="table">
    <thead class='thead-dark'>
      <tr>
        <th style='width: 23%' class='text-left'><?php echo $string_BasinArea ?><strong></th>
        <th style='width: 20%'></th>
        <th style='width: 20%' class='text-center'><strong><?php echo $sumBasinArea ?></strong></th>
        <th style='width: 25%'></th>
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

  document.getElementById("edit_brief").addEventListener("submit", function(event) {
    event.preventDefault();
  });

  function submitForm(title, year_start, year_end, province_unit, basin_unit, prev_year_start, prev_year_end) {
    var headerID = ['dimen', 'group', 'subgroup', 'key', 'id', 'province', 'unit'];
    var insertBefortStart = [];
    var removeBefortStart = [];
    var insertAfterEnd = [];
    var removeAfterEnd = [];
    var insertStartMessage = ""
    var insertEndMessage = ""
    var removeStartMessage = "";
    var removeEndMessage = "";

    for (var i = year_start; i <= year_end; i++) {
      headerID.push(parseInt(i))
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
      delSign = "<br/> <i class='fas fa-minus-circle'></i> "
      removeEndMessage += " will be deleted"
    }
    if (insertStartMessage !== "" || insertEndMessage !== "") {
      addSign = "<br/> <i class='fas fa-plus-circle'></i> "
      insertEndMessage += " will be inserted"
    }
    if ((year_start == prev_year_start) && (year_end == prev_year_end)) {

    } else {
      message = "The Year Range Will Change" +
        "<br/>&nbsp;&nbsp;&nbsp;&nbsp;" +
        "FROM <strong>[" + prev_year_start + ", " + prev_year_end + "]</strong>" +
        "<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" +
        "TO <strong>[" + headerID[7] + ", " + headerID[headerID.length - 1] + "]</strong><br/>" +
        addSign + insertStartMessage + insertEndMessage +
        delSign + removeStartMessage + removeEndMessage +
        "<br/><br/><i class='fas fa-exclamation-triangle'></i> Note that" +
        "<br/>You cannot <strong>RESTORE</strong> the data that going to be deleted";
    }

    // INSERT NEW COLS -> CLEAR HEADER ROW -> INSERT NEW HEADER
    // [2005, 2015]
    // new = [2003, 2017] -> INSERT + APPEND(Nothing to do)
    // new = [2007, 2013] -> REMOVE_FISRT_TWO + REMOVE_LAST_TWO 
    $('#confirmEditBriefContent').html(message);
    $('#editBriefModal').modal('hide');
    $('#confirmDialog').modal('show');
    $('#confirmEditBrief').click(function() {
      // IMPORTANCE
      /////////////////////////////////////////////////////////////////
      const YEAR_RANGE_SHEETS_GID = [1478239757];
      const YEAR_RANGE_SHEETS = ['TESTWA!1:1'];
      /////////////////////////////////////////////////////////////////
      if (removeAfterEnd.length !== 0) {
        deleteYearAfter();
      } else if (removeBefortStart.length !== 0) {
        deleteYearBefore();
      } else if (insertBefortStart.length !== 0) {
        insertYearBefore();
      } else if (insertAfterEnd.length !== 0) {
        clearHeader();
      } else {
        // Northing about year range change
        updateOverview();
      }

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
            $('#confirmDialog').modal('hide');
          }
        });
      }

      // [FIRST PRIORITY]
      function deleteYearAfter() {
        const startIndex = 7; // DO NOT CHANGE THIS VALUE
        console.log('PROCESSING REMOVE COLS AFTER END YEAR')
        const prevLastIndex = (prev_year_end - prev_year_start) + startIndex + 1;
        const startRemoveIndex = prevLastIndex - removeAfterEnd.length;
        for (var i = 0; i < YEAR_RANGE_SHEETS_GID.length; i++) {
          $.ajax({
            url: "actions/batch_delete_cols.php",
            type: 'post',
            data: {
              'id': "DELETE_COLS",
              'sheetId': YEAR_RANGE_SHEETS_GID[i],
              'startIndex': startRemoveIndex,
              'endIndex': prevLastIndex
            },
            success: function(response) {
              console.log("SUCCESS REMOVE COLS AFTER END YEAR")
              if (removeBefortStart.length !== 0) {
                deleteYearBefore();
              } else if (insertBefortStart.length !== 0) {
                insertYearBefore();
              } else {
                clearHeader();
              }
            }
          })
        }
      }

      function deleteYearBefore() {
        const startIndex = 7; // DO NOT CHANGE THIS VALUE
        console.log('PROCESSING REMOVE COLS BEFORE START YEAR')
        for (var i = 0; i < YEAR_RANGE_SHEETS_GID.length; i++) {
          $.ajax({
            url: "actions/batch_delete_cols.php",
            type: 'post',
            data: {
              'id': "DELETE_COLS",
              'sheetId': YEAR_RANGE_SHEETS_GID[i],
              'startIndex': startIndex,
              'endIndex': startIndex + removeBefortStart.length
            },
            success: function(response) {
              console.log("SUCCESS REMOVE COLS BEFORE START YEAR")
              clearHeader();
            }
          })
        }
      }
      /////////////////////////////////////////////////////////////////
      function insertYearBefore() {
        const startIndex = 7; // DO NOT CHANGE THIS VALUE
        console.log('PROCESSING INSERT COLS BEFORE START YEAR')
        for (var i = 0; i < YEAR_RANGE_SHEETS_GID.length; i++) {
          $.ajax({
            url: "actions/batch_insert_cols.php",
            type: 'post',
            data: {
              'id': "INSERT_COLS",
              'sheetId': YEAR_RANGE_SHEETS_GID[i],
              'startIndex': startIndex,
              'endIndex': startIndex + insertBefortStart.length
            },
            success: function(response) {
              console.log("SUCCESS INSERT COLS BEFORE START YEAR")
              clearHeader();
            }
          })
        }
      }
      ////////////////////////////////////////////////////
      function clearHeader() {
        console.log('[2] PROCESSING CLEAR HEADER')
        for (var i = 0; i < YEAR_RANGE_SHEETS_GID.length; i++) {
          $.ajax({
            url: "actions/batch_clear_rows.php",
            type: 'post',
            data: {
              'id': "CLEAR_HEADER",
              'sheetId': YEAR_RANGE_SHEETS_GID[i],
              'startRowIndex': 0,
              'endRowIndex': 1
            },
            success: function(response) {
              console.log("SUCCESS CLEAR HEADER")
              insertHeader();
            }
          })
        }
      }

      function insertHeader() {
        console.log('[3] PROCESSING INSERT NEW HEADER')
        for (var i = 0; i < YEAR_RANGE_SHEETS.length; i++) {
          $.ajax({
            url: "actions/act_insert_value_row.php",
            type: 'post',
            data: {
              'id': "INSERT_ROW",
              'range': YEAR_RANGE_SHEETS[i],
              'data': headerID,
            },
            success: function(response) {
              console.log("SUCCESS INSERT NEW HEADER")
              updateOverview();
            }
          })
        }
      }
    })
  }

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
</script>