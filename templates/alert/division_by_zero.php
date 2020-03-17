<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>DIVISIBLE BY ZERO</strong>
  <?php
  if (isset($LOCATION)) {
    echo "on <strong>" . $LOCATION . "</strong>";
  }
  ?>
  Some data might not exist and be replaced by 0 !!!
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>