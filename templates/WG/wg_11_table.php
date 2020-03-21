<H3 style="margin-bottom:40px"><strong><?php echo "[ WG1 ] " . $WeightKeysData['WG1']['name'] ?></strong></H3>
<!-- ------------------------------------------------- -->
<H4 style="margin-bottom:20px">
  <a><a href="#" id=<?php echo "import-wg-11" ?>><i style="font-size:20px" class="fas fa-user-plus"></i></a>
    <?php echo $WeightKeysData['WG11']['id'] ?> : <?php echo $WeightKeysData['WG11']['name'] ?>
  </a>
</H4>
<!-- --------------------------------------------------- -->
<div class="modal fade" id="addRespondentModal_wg11" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-user-plus"></i> Add New Respondent</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <span class="input-group-text" id="basic-addon1">Name</span>
          </div>
          <input id="addNewRespondent_wg11" type="text" class="form-control" placeholder="Name" aria-label="Name" aria-describedby="basic-addon1">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button id="addRespondent_wg11" type="button" class="btn btn-primary">Add</button>
      </div>
    </div>
  </div>
</div>
<!-- ------------------------------------------------- -->
<div style="margin-top:30px" class='table-responsive'>
  <table id="editable_wg_11" class='table table-hover'>
    <thead class='thead-dark'>
      <tr>
        <th style="width:40px;"></th>
        <th scope='col' class='text-center'>#</th>
        <th scope='col' class='text-left' style="white-space: nowrap;">Name of the Respondent</th>
        <?php
        for ($i = 1; $i <= sizeof($RespondentList['WG1']['WG11'][array_key_first($RespondentList['WG1']['WG11'])]['score']); $i++) {
        ?>
          <th class='text-center'><?php echo "Q" . $i ?></th>
        <?php }
        ?>
      </tr>
    </thead>
    <tbody>
      <?php
      $index = 1;
      foreach ($RespondentList['WG1']['WG11'] as $rId => $res) { ?>
        <tr>
          <td><a id="DeleteButton" thisId="<?php echo $rId ?>" name="<?php echo $res['name']; ?>" class="text-secondary" href="#" class="text-right"><i class="fas fa-trash"></i></a></td>
          <td class="text-center"><?php echo $index; ?></td>
          <td class="text-left"><?php echo $res['name']; ?></td>
          <?php foreach ($res['score'] as $q => $value) { ?>
            <td style="cursor: pointer" id="EditText" class='text-center table-success'>
              <a name="<?php echo $rId; ?>" id="<?php echo $q; ?>" data-editable-wg11><?php echo $value; ?></a>
            </td>
          <?php }
          ?>
        </tr>
      <?php $index++;
      } ?>
    </tbody>
  </table>
</div>
<?php include  __DIR__ . "./../../templates/utils/hr.html"; ?>
<!-- ------------------------------------------------- -->
<?php include  __DIR__ . "./../../templates/WG/weight_table/wg1_weight_table.php"; ?>
<!-- -------------------------------------------------------------------------------- -->
<script type="text/javascript">
  $("#editable_wg_11").on("click", "#DeleteButton", function() {
    $el = $(this);
    var id = $el.attr('thisId');
    var name = $el.attr('name');
    if (confirm("Do you want to delete " + name + " ?")) {
      if (id !== null) {
        $.ajax({
          url: 'actions/act_delete_rows.php',
          type: 'post',
          data: {
            id_column: "D",
            id: id,
            sheet_id: <?php echo json_encode($RESPONDENT_LIST_SHEET); ?>,
            gid: <?php echo json_encode($RESPONDENT_LIST_GID); ?>,
          },
          success: function(response) {
            $el.closest("tr").remove();
            $('#loading').show()
            $('#editable_wg_11').load(location.href + " #editable_wg_11", function() {
              $("#wg-WG1-score-table").load(location.href + " #wg-WG1-score-table");
              $('#loading').hide()
            });
          }
        });
      }
    }
    return false;
  });
  ///////////////////////////////////////
  $('#import-wg-11').click(function() {
    $('#addRespondentModal_wg11').modal('show');
  });
  $('#addRespondent_wg11').click(function() {
    ///////////////////////////////////////
    var lastQuestion = $('#editable_wg_11 tr td a').last().attr('id')
    var lastResId = $('#editable_wg_11 tr td a').last().attr('name')
    var temp = "";
    for (var i = lastResId.length - 1; i >= 0; i--) {
      if (lastResId[i] == "_") {
        break;
      } else {
        temp = lastResId[i] + temp;
      }
    }
    var resKey = lastResId.replace("_" + temp, '');
    var nextId = resKey + "_" + (parseInt(temp) + 1);
    ///////////////////////////////////////
    var name = $('#addNewRespondent_wg11').val()
    if (name !== "") {
      var questions = [];
      for (var i = 0; i < parseInt(lastQuestion.replace("Q", '')); i++) {
        questions.push(0)
      }
      var newData = ['WG1', 'WG11', 'WG11', nextId, name, ...questions];

      $.ajax({
        url: "actions/act_append.php",
        type: 'post',
        data: {
          'id': 'addNewRespondent_wg11',
          'data': newData,
          'sheet_id': <?php echo json_encode($RESPONDENT_LIST_SHEET); ?>,
        },
        success: function(response) {
          $('#addRespondentModal_wg11').modal('hide');
          $('#loading').show()
          $('#editable_wg_11').load(location.href + " #editable_wg_11", function() {
            $("#wg-WG1-score-table").load(location.href + " #wg-WG1-score-table");
            $('#loading').hide()
            var $target = $('html,body');
            $target.animate({
              scrollTop: $target.height()
            }, 1000);
            $('#addNewRespondent_wg11').val('');
          });
        }
      });
    } else {
      alert("Cannot be empty")
    }
  })
  ////////////////////////////////////////////////////////////
  $('body').on('click', '[data-editable-wg11]', function(e) {
    e.preventDefault();
    var $el = $(this);
    var $input = $('<input type="number" id="' + $el.attr("id") + '" name="' + $el.attr("name") + '" type="text" style="min-width:100px;" class="form-control">').val($el.text());
    $el.replaceWith($input);
    var save = function() {
      var $a = $('<a id="' + $el.attr("id") + '" name="' + $el.attr("name") + '" data-editable-wg11 />').text($input.val());
      $input.replaceWith($a);
    };
    $input.one('blur', save).focus();
    ////////////////////////////////////////////////////////////////////
    function callAjax(data) {
      return $.ajax({
        url: 'actions/act_edit_cell.php',
        type: 'post',
        data: data,
        success: function(response) {
          $("#wg-WG1-score-table").load(location.href + " #wg-WG1-score-table");
        },
      })
    }
    ////////////////////////////////////////////////////////////////////
    $input.keydown(function(e) {
      console.log($el.attr("name"), $el.attr("id"));
      if (e.keyCode == 13 | e.keyCode == 9) {
        callAjax({
          id: $el.attr("name"),
          sheet_id: <?php echo json_encode($RESPONDENT_LIST_SHEET); ?>,
          year: $el.attr("id"),
          val: $input.val(),
        });
        $input.blur();
      };
    })
    $input.blur(function() {
      callAjax({
        id: $el.attr("name"),
        sheet_id: <?php echo json_encode($RESPONDENT_LIST_SHEET); ?>,
        year: $el.attr("id"),
        val: $input.val(),
      });
    })
  });
</script>