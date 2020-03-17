<?php

include_once __DIR__ . './../configBatch.php';

if (isset($_POST['id'])) {
  $sheetId = $_POST['sheetId'];
  $startIndex = $_POST['startIndex'];
  $endIndex = $_POST['endIndex'];

  $request = new \Google_Service_Sheets_UpdateCellsRequest([
    'deleteDimension' => [
      'range' => [
        'sheetId' => $sheetId,
        'dimension' => 'COLUMNS',
        'startIndex' => $startIndex,
        'endIndex' => $endIndex
      ]
    ],
  ]);
  $requests[] = $request;
  $requestBody = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest();
  $requestBody->setRequests($requests);
  $response = $service->spreadsheets->batchUpdate($spreadsheetID, $requestBody);
}
