<?php

function getSpecificInput($array)
{
  if (empty($array)) {
    echo "<p>No data Found</p>";
  } else {
    foreach ($array[0] as $i => $item) { // Find key
      $columnKey[$i] = $item;
    }
    foreach ($array as $r => $row) {   // Construct data
      if ($r !== 0) {
        foreach ($columnKey as $k => $key) {
          if (isset($row[$k])) {
            $RawSpecificInput[$r - 1][$key] = $row[$k];
          }
        }
      }
    }
  }
  // Construct a new one
  // Using key value instead of index
  $SpecificInput = [];
  foreach ($RawSpecificInput as $item) {
    if (($item['subgroup'] == "")) {
      $SpecificInput[$item['dimen']][$item['group']][$item['id']] = $item;
    } else {
      $SpecificInput[$item['dimen']][$item['group']][$item['subgroup']][$item['id']] = $item;
    }
  }

  return $SpecificInput;
}
