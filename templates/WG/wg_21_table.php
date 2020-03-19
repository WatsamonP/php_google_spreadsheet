<H3 style="margin-bottom:40px"><strong><?php echo "[ WG2 ] " . $WeightKeysData['WG2']['name'] ?></strong></H3>
<!-- ------------------------------------------------- -->
<H4 style="margin-bottom:20px">
  <a><a href="#" id=<?php echo "import-wg-21" ?>><i style="font-size:20px" class="fas fa-user-plus"></i></a>
    <?php echo $WeightKeysData['WG21']['id'] ?> : <?php echo $WeightKeysData['WG21']['name'] ?>
  </a>
</H4>
<!-- --------------------------------------------------- -->
<div class="modal fade" id="addRespondentModal_wg21" tabindex="-1" role="dialog">
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
          <input id="addNewRespondent_wg21" type="text" class="form-control" placeholder="Name" aria-label="Name" aria-describedby="basic-addon1">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button id="addRespondent_wg21" type="button" class="btn btn-primary">Add</button>
      </div>
    </div>
  </div>
</div>
<!-- ------------------------------------------------- -->
<div style="margin-top:30px" class='table-responsive'>
  <table id="editable_wg_21" class='table table-hover'>
    <thead class='thead-dark'>
      <tr>
        <th scope='col' class='text-center'>#</th>
        <th scope='col' class='text-left' style="white-space: nowrap;">Name of the Respondent</th>
        <?php
        for ($i = 1; $i <= sizeof($RespondentList['WG2']['WG21'][array_key_first($RespondentList['WG2']['WG21'])]['score']); $i++) {
        ?>
          <th class='text-center'><?php echo "Q" . $i ?></th>
        <?php }
        ?>
      </tr>
    </thead>
    <tbody>
      <?php
      $index = 1;
      foreach ($RespondentList['WG2']['WG21'] as $rId => $res) { ?>
        <tr>
          <td class="text-center"><?php echo $index; ?></td>
          <td class="text-left"><?php echo $res['name']; ?></td>
          <?php foreach ($res['score'] as $q => $value) { ?>
            <td id="EditText" class='text-center table-success'>
              <a name="<?php echo $rId; ?>" id="<?php echo $q; ?>" data-editable-wg21><?php echo $value; ?></a>
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
<?php include  __DIR__ . "./../../templates/WG/weight_table/wg2_weight_table.php"; ?>
<!-- -------------------------------------------------------------------------------- -->
<script type="text/javascript">
  $('#import-wg-21').click(function() {
    $('#addRespondentModal_wg21').modal('show');
  });
  $('#addRespondent_wg21').click(function() {
    ///////////////////////////////////////
    var lastQuestion = $('#editable_wg_21 tr td a').last().attr('id')
    var lastResId = $('#editable_wg_21 tr td a').last().attr('name')
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
    var name = $('#addNewRespondent_wg21').val()
    if (name !== "") {
      var questions = [];
      for (var i = 0; i < parseInt(lastQuestion.replace("Q", '')); i++) {
        questions.push(0)
      }
      var newData = ['WG2', 'WG21', 'WG21', nextId, name, ...questions];

      $.ajax({
        url: "actions/act_append.php",
        type: 'post',
        data: {
          'id': 'addNewRespondent_wg21',
          'data': newData,
          'sheet_id': "RespondentList",
        },
        success: function(response) {
          $('#addRespondentModal_wg21').modal('hide');
          $('#loading').show()
          $('#editable_wg_21').load(location.href + " #editable_wg_21", function() {
            $("#wg-WG2-score-table").load(location.href + " #wg-WG2-score-table");
            $('#loading').hide()
            var $target = $('html,body');
            $target.animate({
              scrollTop: $target.height()
            }, 1000);
            $('#addNewRespondent_wg21').val('');
          });
        }
      });
    } else {
      alert("Cannot be empty")
    }
  })
  ////////////////////////////////////////////////////////////
  $('body').on('click', '[data-editable-wg21]', function(e) {
    e.preventDefault();
    var $el = $(this);
    var $input = $('<input type="number" id="' + $el.attr("id") + '" name="' + $el.attr("name") + '" type="text" style="min-width:100px;" class="form-control">').val($el.text());
    $el.replaceWith($input);
    var save = function() {
      var $a = $('<a id="' + $el.attr("id") + '" name="' + $el.attr("name") + '" data-editable-wg21 />').text($input.val());
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
          $("#wg-WG2-score-table").load(location.href + " #wg-WG2-score-table");
        },
      })
    }
    ////////////////////////////////////////////////////////////////////
    $input.keydown(function(e) {
      console.log($el.attr("name"), $el.attr("id"));
      if (e.keyCode == 13 | e.keyCode == 9) {
        callAjax({
          id: $el.attr("name"),
          sheet_id: "RespondentList",
          year: $el.attr("id"),
          val: $input.val(),
        });
        $input.blur();
      };
    })
    $input.blur(function() {
      callAjax({
        id: $el.attr("name"),
        sheet_id: "RespondentList",
        year: $el.attr("id"),
        val: $input.val(),
      });
    })
  });
</script>