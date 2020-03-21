<?php

include_once __DIR__ . './../config.php';
include_once __DIR__ . './../configBatch.php';

if (isset($_POST['id'])) {
  // FIND ROW ID e.g. 2 | 3 |4
  // ID is on A column
  $ID_COLUMN = $_POST['id_column'];
  $RANGE_COL = $_POST['sheet_id'] . "!" . $ID_COLUMN . ":" . $ID_COLUMN;
  $responseCol = $service->spreadsheets_values->get($spreadsheetId, $RANGE_COL);
  $arrayCol = $responseCol->getValues();
  $ROW_ID = 0;
  foreach ($arrayCol as $key => $item) {
    if ($item[0] == $_POST['id']) {
      $ROW_ID = $key + 1;
    }
  }

  $gid = $_POST['gid'];
  if ($ROW_ID !== 0) {
    $request = new \Google_Service_Sheets_UpdateCellsRequest([
      'deleteDimension' => [
        'range' => [
          'sheetId' => $gid,
          'dimension' => 'ROWS',
          'startIndex' => ($ROW_ID - 1),
          'endIndex' => $ROW_ID
        ]
      ],
    ]);
    $requests[] = $request;
    $requestBody = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest();
    $requestBody->setRequests($requests);
    $response = $service->spreadsheets->batchUpdate($spreadsheetID, $requestBody);
  }
}

echo json_encode($ROW_ID-1);
