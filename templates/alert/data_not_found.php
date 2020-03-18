<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>DATA NOT FOUND</strong>
  <?php
  if (isset($LOCATION)) {
    echo "At <strong>" . $LOCATION . "</strong>";
  }
  ?>
  (The calculation still working but the missing value will be auto-replace by 0) <br />
  - Some data might be <strong>empty</strong> <br />
  - Note that this might be an effect of changing the year range value. Please fill the missing value.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>