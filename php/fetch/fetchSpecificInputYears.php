<?php

function getSpecificInputYears($array)
{
  if (empty($array)) {
    echo "<p>No data Found</p>";
  } else {
    foreach ($array[0] as $i => $item) { // Find key
      $columnSIKey[$i] = $item;
    }
    foreach ($array as $r => $row) {   // Construct data
      if ($r !== 0) {
        foreach ($columnSIKey as $k => $key) {
          if (isset($row[$k])) {
            $RawSpecificInputYearsArray[$r - 1][$key] = $row[$k]; // GRAP ALL DATA
            if (startsWithNumber($key)) {
              $SpecificInputYearsArray[$r - 1][$key] = $row[$k];   // GRAP ONLY YEARS
            }
          } else {
            $RawSpecificInputYearsArray[$r - 1][$key] = 0; // GRAP ALL DATA
            if (startsWithNumber($key)) {
              $SpecificInputYearsArray[$r - 1][$key] = 0;   // GRAP ONLY YEARS
            }
            $LOCATION = $RawSpecificInputYearsArray[$r - 1]['key'];
            include __DIR__ . "./../../templates/alert/data_not_found.php";
          }
        }
      }
    }
  }
  // Construct a new one
  // Using key value instead of index
  $SpecificInputYears = [];
  foreach ($RawSpecificInputYearsArray as $item) {
    foreach ($item as $key => $value) {
      if (isset($key)) {
        if (startsWithNumber($key)) {
          if (empty($value)) {
            $SpecificInputYears[$item['dimen']][$item['group']][$item['id']]['table'][$key] = (float) 0;
          } else {
            $SpecificInputYears[$item['dimen']][$item['group']][$item['id']]['table'][$key] = $value;
          }
        } else {
          $SpecificInputYears[$item['dimen']][$item['group']][$item['id']][$key] = $value;
        }
      }
    }
  }

  return $SpecificInputYears;
}
