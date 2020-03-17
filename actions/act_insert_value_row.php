<?php

include_once __DIR__ . './../config.php';

if (isset($_POST['id'])) {
  $range = $_POST['range'];
  $values = [$_POST['data']];

  $body = new Google_Service_Sheets_ValueRange([
    'values' => $values
  ]);

  $service->spreadsheets_values->update(
    $spreadsheetId,
    $range,
    $body,
    ['valueInputOption' => 'Raw']
  );
}

echo json_encode($_POST);
