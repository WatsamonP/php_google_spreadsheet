<div style="margin-top:30px">
  <H5>
    <i class="fas fa-clipboard-list"></i> #WP Table Name#
  </H5>
  <!--  -->
  <div class="card">
    <div class="card-body">
      <div class='table-responsive'>
        <table class='table table-hover'>
          <thead class="thead-dark">
            <tr">
              <th scope='col' class='text-left'>Year</th>
              <?php
              foreach ($YEAR_RANGE as $year) {
              ?>
                <th class='text-right'><?php echo $year ?></th>
              <?php }
              ?>
              </tr>
          </thead>
          <tbody>
            <?php foreach ($WP_TB_TEMP as $key => $item) { ?>
              <tr class="table-active">
                <td style="white-space: nowrap;"><?php echo $item['key'] ?></td>
                <?php foreach ($item['table'] as $value) { ?>
                  <td class='text-right'><?php echo number_format($value, 2, '.', '')  ?></td>
                  <?php
                  ?>
                <?php }
                ?>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>