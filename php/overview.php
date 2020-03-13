<?php
$range = "Provinces";

$response = $service->spreadsheets_values->get($spreadsheetId, $range);
$array = $response->getValues();

if (empty($array)) {
  echo "<p>No data Found</p>";
} else {

  // Find key
  foreach ($array[0] as $i => $item) {
    $provincesHead[$i] = $item;
  }

  // Construct data
  foreach ($array as $r => $row) {
    if ($r !== 0) {
      foreach ($provincesHead as $h => $key) {
        $provincesData[$r - 1][$key] = $row[$h];
      }
    }
  }
}
