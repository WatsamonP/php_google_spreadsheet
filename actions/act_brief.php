<?php

include_once '../config.php';

// [TAB] OVERVIEW
// [RANGE] A1:C5
$RANGE = "C1:C5";

$title = $_POST['title'];
$year_start = $_POST['year_start'];
$year_end = $_POST['year_end'];
$province_unit = $_POST['province_unit'];
$basin_unit = $_POST['basin_unit'];

$values = [[$title], [$year_start], [$year_end], [$province_unit], [$basin_unit]];

$body = new Google_Service_Sheets_ValueRange([
  'values' => $values
]);

$param = [
  'valueInputOption' => 'Raw'
];

$service->spreadsheets_values->update(
  $spreadsheetId,
  $RANGE,
  $body,
  $param
);
