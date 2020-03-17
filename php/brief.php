<?php
require_once __DIR__. "./../constants/keys.php";
$range = $OVERVIEW_SHEET . "!" . $OVERVIEW_RANGE;

/****************************************************************************** */
$response = $service->spreadsheets_values->get($spreadsheetId, $range);
$array = $response->getValues();

if (empty($array)) {
  echo "<p>No data Found</p>";
} else {
  $title = $array[0];
  $year_start = $array[1];
  $year_end = $array[2];
  $province_unit = $array[3];
  $basin_unit = $array[4];
}
