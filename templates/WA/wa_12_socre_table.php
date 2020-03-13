<div style="margin-top:30px" class='table-responsive'>
  <H5>
    <i class="fas fa-clipboard-list"></i> #Table Name#
  </H5>
  <!--  -->
  <table class='table table-hover' style="display: block !important;">
    <thead class='thead-dark'>
      <tr>
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
      <?php foreach ($WA12_TB as $key => $WA12_item) { ?>
        <tr>
          <td style="white-space: nowrap;"><?php echo $WA12_item['key'] ?></td>
          <?php foreach ($WA12_item['table'] as $WA12_data) { ?>

            <td class='text-right'><?php echo number_format($WA12_data, 2, '.', '')  ?></td>
            <?php
            ?>
          <?php }
          ?>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>