<div class="container">
  <div class="row justify-content-center">
    <div class="col-auto d-flex order-1">
      <div class="card border-success mb-3">
        <div class="card-header text-success">Weight</div>
        <div class="card-body">
          <table class='table table-responsive table-hover' style="display: block !important;">
            <thead class='thead-dark'>
              <tr class="text-center">
                <th>Variable</th>
                <th>Weight</th>
              </tr>
            </thead>
            <tbody>
              <tr  class="text-center">
                <td>WA11</td>
                <td class="table-success"><a data-editable><?php echo $WA11_Weight ?></a></td>
              </tr>
              <tr>
                <td>WA12</td>
                <td class="table-success"><a data-editable><?php echo $WA12_Weight ?></a></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

    </div>
    <div class="col-auto d-flex order-2">
      <div class="card border-warning mb-3">
        <div class="card-header text-warning">Score</div>
        <div class="card-body">
          <table class='table table-responsive table-hover' style="display: block !important;">
            <thead class='thead-dark'>
              <tr>
                <th scope='col' class='text-center'>Year</th>
                <?php
                foreach ($YEAR_RANGE as $year) {
                ?>
                  <th class='text-center'><?php echo $year ?></th>
                <?php }
                ?>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class='text-center table-warning'></td>
                <?php foreach ($WA1_SCORE as $score_wa1) { ?>
                  <td class='text-center table-warning'>
                    <?php echo number_format($score_wa1, 2, '.', '')  ?>
                  </td>
                <?php }
                ?>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>