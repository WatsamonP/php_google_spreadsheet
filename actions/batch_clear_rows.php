<?php

include_once __DIR__ . './../configBatch.php';

if (isset($_POST['id'])) {
  $sheetId = $_POST['sheetId'];
  $startRowIndex = $_POST['startRowIndex'];
  $endRowIndex = $_POST['endRowIndex'];

  $request = new \Google_Service_Sheets_UpdateCellsRequest([
    'updateCells' => [
      'range' => [
        'sheetId' => $sheetId,
        "startRowIndex" => $startRowIndex,
        "endRowIndex" => $endRowIndex,
      ],
      'fields' => "*" //clears everything
    ]
  ]);
  $requests[] = $request;
  $requestBody = new Google_Service_Sheets_BatchUpdateSpreadsheetRequest();
  $requestBody->setRequests($requests);
  $response = $service->spreadsheets->batchUpdate($spreadsheetID, $requestBody);
}
