<?php

include_once __DIR__ . './../configBatch.php';

if (isset($_POST['id'])) {
  $sheetId = $_POST['sheetId'];
  $startIndex = $_POST['startIndex'];
  $length = $_POST['length'];

  $request = new \Google_Service_Sheets_UpdateCellsRequest([
    'appendDimension' => [
      'range' => [
        'sheetId' => $sheetId,
        'dimension' => 'COLUMNS',
        'length' => $startIndex,
      ]
    ],
  ]);
  $requests[] = $request;
  $requestBody = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest();
  $requestBody->setRequests($requests);
  $response = $service->spreadsheets->batchUpdate($spreadsheetID, $requestBody);
}
