<?php

include_once '../config.php';

if (isset($_POST['id'])) {
  // FIND ROW ID e.g. 2 | 3 |4
  // ID is on A column
  $ID_COLUMN = "E";
  $RANGE_COL = $_POST['sheet_id'] . "!" . $ID_COLUMN . ":" . $ID_COLUMN;
  $responseCol = $service->spreadsheets_values->get($spreadsheetId, $RANGE_COL);
  $arrayCol = $responseCol->getValues();
  $ROW_ID = 0;
  foreach ($arrayCol as $key => $item) {
    if ($item[0] == $_POST['id']) {
      $ROW_ID = $key + 1;
    }
  }

  $val = $_POST['val'];
  $reqRange = $_POST['sheet_id'] . "!F"  . $ROW_ID;
  $values = [[(float) $val]];

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

echo json_encode($_POST);
