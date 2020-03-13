<?php
require_once "config.php";
require_once "php/brief.php";
require_once "php/overview.php";
require_once "constants/word.php";
?>

<nav class="navbar navbar-light bg-light">
  <div type="button" id="sidebarCollapse">
    <i id="sidebarIcon" class="fas fa-2x fa-caret-square-left"></i>
  </div>
  <form class="form-inline">
    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#exampleModal">
      <i class="fas fa-edit"></i> Edit
    </button>
  </form>
</nav>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Information</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="edit_brief" onsubmit="submitForm(this.title.value, this.year_start.value, this.year_end.value, this.province_unit.value, this.basin_unit.value)" method="post">
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
            <input name="year_start" value="<?php echo $year_start[2] ?>" type="text" class="form-control">
            <input name="year_end" value="<?php echo $year_end[2] ?>" type="text" class="form-control">
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
    $sumBasinArea = 0;
    foreach ($provincesData as $index => $province) {
      $key = $index + 1;
      $sumBasinArea += $province[$string_basinArea];
      ### CALCULATION
      $ratio = $province[$string_basinArea] / $province[$string_provinceArea];
    ?>
      <tr>
        <th scope='row'><?php echo $key ?></th>
        <td style='display: none'><?php echo $province['id'] ?></td>
        <td><?php echo $province['Name of the Province'] ?></td>
        <td class='text-center'><?php echo number_format($province[$string_provinceArea], 2, '.', '') ?></td>
        <td class='text-center'><?php echo number_format($province[$string_basinArea], 2, '.', '') ?></td>
        <td class='text-center'><?php echo number_format($ratio, 2, '.', '') ?></td>
      </tr>
    <?php }; ?>
  </tbody>
</table>

<!-- <table class="table">
  <thead class='table-active'>
    <tr>
      <th style='width: 10%' scope='row'></th>
      <th style='display: none'></th>
      <td style='width: 25%'><strong><?php echo $string_BasinArea ?><strong></td>
      <td style='width: 20%'></td>
      <td style='width: 20%' class='text-center'><strong><?php echo $sumBasinArea ?></strong></td>
      <td style='width: 25%' class='text-center'></td>
    </tr>
  </thead>
</table> -->

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

  function submitForm(title, year_start, year_end, province_unit, basin_unit) {
    $.ajax({
      url: "actions/act_brief.php",
      type: 'post',
      data: {
        'title': title,
        'year_start': year_start,
        'year_end': year_end,
        'province_unit': province_unit,
        'basin_unit': basin_unit
      },
      success: function(response) {
        console.log('SUCCESS')
      }
    })
  }

  $(document).ready(function() {
    $('#editable_table').Tabledit({
      url: 'actions/act_overview.php',
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
          url: 'actions/act_post.php',
          type: 'post',
          data: data,
          success: function(response) {
            console.log('SUCCESS')
            $('#loading').show();
            setTimeout(function() {
              location.reload()
            }, 100);
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