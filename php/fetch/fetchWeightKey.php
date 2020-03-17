<?php

/****************************** */
// PULL DATA from WEIGHT SHEET //
/****************************** */
function getWeightKey($array)
{
  $WeightKeysData = [];
  if (empty($array)) {
    echo "<p>No data Found</p>";
  } else {
    foreach ($array[0] as $i => $item) { // Find key
      $columnSKKey[$i] = $item;
    }
    foreach ($array as $r => $row) {   // Construct data
      if ($r !== 0) {
        foreach ($columnSKKey as $k => $key) {
          $WeightKeysData[$row[0]][$key] = $row[$k];
        }
      }
    }
  }
  return $WeightKeysData;
};

function combineWeightKey($WX_SET, $WeightKeysData)
{
  foreach ($WX_SET as $sKey => $set) {
    foreach ($set as $gKey => $group) {
      if (preg_match("/_[A-Z]/i", $gKey)) {
        $temp_KEY = preg_replace("/_[A-Z]/i", "", $gKey);
        $WX_SET[$sKey][$gKey]['name'] = $WeightKeysData[$temp_KEY]['name'];
      } else {
        $WX_SET[$sKey][$gKey]['name'] = $WeightKeysData[$gKey]['name'];
      };
    }
  }
  return $WX_SET;
}
