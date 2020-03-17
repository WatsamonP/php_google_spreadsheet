<!-- <div id="dataNotFound" style="display: none" class="alert alert-danger" role="alert">
  [NOTE] <a href="#" class="alert-link"></a>DATA IS NOT EXIST</a> | The Calculation will replace data by 0
</div> -->

<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>DATA NOT FOUND</strong>
  <?php
  if (isset($LOCATION)) {
    echo "At <strong>" . $LOCATION . "</strong>";
  }
  ?>
  (Auto replace by 0) <br />
  This might be an effect of changing the year range value. Please fill the missing value.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>