<?php

function getWxData($array, $ratio)
{
  $WX_SET = [];
  if (empty($array)) {
    echo "<p>No data Found</p>";
  } else {
    foreach ($array[0] as $i => $item) { // Find key (the header)
      $columnKey[$i] = $item;
    }

    print_r(isset($var));
    foreach ($array as $r => $row) {   // Construct data
      if ($r !== 0) {
        foreach ($columnKey as $k => $key) {
          if (isset($row[$k])) {
            $data[$r - 1][$key] = $row[$k];
          } else {
            $data[$r - 1][$key] = 0;
            include_once __DIR__ . "./../../templates/alert/data_not_found.php";
          }
        }
      }
    }

    /******************************************** */
    // CONSTRUCT TABLE FOR WP1X [get GREEN TABLE] //
    /******************************************** */
    $WP_DIMEN = array_unique(array_column($data, 'dimen'));
    $WP_GROUP = array_unique(array_column($data, 'subgroup'));
    $WX_SET = []; // contains WP1, WP2, WP3, WP4 ...
    $YEAR_RANGE = [];
    foreach ($WP_DIMEN as $dimen) {
      foreach ($WP_GROUP as $group) {
        foreach ($data as $row) {
          if ($row['dimen'] == $dimen) {
            if ($row['subgroup'] == $group) {
              foreach (array_keys($row) as $col) {
                if (startsWithNumber($col)) {
                  $WX_SET[$dimen][$group]['data'][$row['id']]['table'][$col] = $row[$col];
                  $YEAR_RANGE[$col] = $col;
                } else if ($col == 'key') {
                  $WX_SET[$dimen][$group]['key'] = $row[$col];
                } else if ($col == 'unit') {
                  $WX_SET[$dimen][$group]['unit'] = $row[$col];
                } else {
                  $WX_SET[$dimen][$group]['data'][$row['id']][$col] = $row[$col];
                }
              }
            }
          }
        }
      }
    }

    /************************************************** */
    // CONSTRUCT CALCULATION TABLE FOR WP1X [RED TABLE] //
    /************************************************** */
    foreach ($WX_SET as $dKey => $dimen) {
      foreach ($dimen as $gKey => $group) {
        $index = 0;
        foreach ($group['data'] as $iKey => $item) {
          $row = $item['table'];
          foreach ($row as $key => $cell) {
            if (isset($cell)) {
              $cal = (float) $cell * (float) $ratio[$index];
            } else {
              $cal = 0;
            }
            $WX_SET[$dKey][$gKey]['data'][$iKey]['cal_table'][$key] = $cal;
          }
          $index++;
        }
      }
    }
  }
  return ["SET" => $WX_SET, "YEAR_RANGE" => $YEAR_RANGE];
}
