<?php
require_once __DIR__ . "./../config.php";
require_once __DIR__ . "./../constants/keys.php";
require_once __DIR__ . "./../constants/word.php";
// function
require_once __DIR__ . "./../php/calculation/utils.php";

$rangeP = $PROVINCES_SHEET;
$responseP = $service->spreadsheets_values->get($spreadsheetId, $rangeP);
$arrayP = $responseP->getValues();

if (empty($arrayP)) {
  echo "<p>No data Found</p>";
} else {

  // Find key
  foreach ($arrayP[0] as $i => $item) {
    $provincesHead[$i] = $item;
  }

  // Construct data
  foreach ($arrayP as $r => $row) {
    if ($r !== 0) {
      foreach ($provincesHead as $h => $key) {
        $provincesData[$r - 1][$key] = $row[$h];
      }
    }
  }
}

/************************************ */
// CALCULATE RATIO AND SUM BASIN AREA //
/************************************ */
$ratio = getRatio($provincesData, $string_provinceArea, $string_basinArea);
$sumBasinArea = getSumBasinArea($provincesData, $string_basinArea);

// print_r($sumBasinArea);
