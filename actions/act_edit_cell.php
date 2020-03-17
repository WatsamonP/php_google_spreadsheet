<?php

include_once '../config.php';

if (isset($_POST['id'])) {
  // FIND ROW ID e.g. 2 | 3 |4
  // ID is on D column
  $ID_COLUMN = "D";
  $RANGE_COL = $_POST['sheet_id'] . "!" . $ID_COLUMN . ":" . $ID_COLUMN;
  $responseCol = $service->spreadsheets_values->get($spreadsheetId, $RANGE_COL);
  $arrayCol = $responseCol->getValues();
  $ROW_ID = 0;
  foreach ($arrayCol as $key => $item) {
    if ($item[0] == $_POST['id']) {
      $ROW_ID = $key + 1;
    }
  }

  // Find COLUMN ID e.g. 2005, 2006
  $RANGE_ROW = $_POST['sheet_id'] . "!1:1";
  $responseRow = $service->spreadsheets_values->get($spreadsheetId, $RANGE_ROW);
  $arrayRow = $responseRow->getValues();
  $COL_ID = 0;
  print_r($arrayRow);
  foreach ($arrayRow[0] as $key => $item) {
    if ($item == $_POST['year']) {
      $COL_ID = $key;
    }
  }
  $alphabet = range('A', 'Z');
  $COL_LETTER = $alphabet[$COL_ID];

  /*************** */
  // CALL SERVICE // 
  /*************** */
  $val = $_POST['val'];
  $reqRange = $_POST['sheet_id'] . "!" . $COL_LETTER . $ROW_ID;
  $values = [[$val]];

  $body = new Google_Service_Sheets_ValueRange([
    'values' => $values
  ]);

  $service->spreadsheets_values->update(
    $spreadsheetId,
    $reqRange,
    $body,
    ['valueInputOption' => 'Raw']
  );
}
