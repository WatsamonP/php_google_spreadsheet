function post_editable_cell($input, $el) {
  $input.keydown(function (e) {
    $val = $input.val()
    // 13 = Enter, 9 = Tab
    if (e.keyCode == 13 | e.keyCode == 9) {
      var data = {
        id: $el.attr("name"),
        sheet_id: "SpecificInputYears",
        year: $el.attr("id"),
        val: $val
      };
      $.ajax({
        url: 'actions/act_edit_cell.php',
        type: 'post',
        data: data,
        success: function (response) {
          $input.blur();
          $("#wa-score-table-final").load(location.href + " #wa-score-table-final");
        },
      })
    }
  })
  /******************* */
  $input.blur(function () {
    var data = {
      id: $el.attr("name"),
      sheet_id: "SpecificInputYears",
      year: $el.attr("id"),
      val: $input.val()
    };
    $.ajax({
      url: 'actions/act_edit_cell.php',
      type: 'post',
      data: data,
      success: function (response) {
        $("#wa-score-table-final").load(location.href + " #wa-score-table-final");
      },
    })
  });
}