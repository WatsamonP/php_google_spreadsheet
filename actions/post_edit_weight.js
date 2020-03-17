function post_edit_weight($input, $el) {
  var this_id = $el.attr("id");
  if (this_id.includes('_final')) {
    this_id = this_id.replace('_final', '')
  }
  /**** */
  $input.keydown(function (e) {
    $val = $input.val()
    // 13 = Enter, 9 = Tab
    if (e.keyCode == 13 | e.keyCode == 9) {
      var data = {
        id: this_id,
        sheet_id: "WeightKey",
        val: $input.val(),
      };
      $.ajax({
        url: 'actions/act_edit_weight.php',
        type: 'post',
        data: data,
        success: function (response) {
          $input.blur();
          $("#wa-score-table").load(location.href + " #wa-score-table");
        },
      })
    }
  })
  /******************* */
  $input.blur(function () {
    var data = {
      id: this_id,
      sheet_id: "WeightKey",
      val: $input.val(),
    };
    $.ajax({
      url: 'actions/act_edit_weight.php',
      type: 'post',
      data: data,
      success: function (response) {
        $("#wa-score-table").load(location.href + " #wa-score-table");
      },
    })
  })
}