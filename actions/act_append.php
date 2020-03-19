<?php
include_once '../config.php';

if (isset($_POST['id'])) {
  $range = $_POST['sheet_id'];
  $data = $_POST['data'];

  $values = [$data];
  $body = new Google_Service_Sheets_ValueRange([
    'values' => $values
  ]);

  $service->spreadsheets_values->append(
    $spreadsheetId,
    $range,
    $body,
    ['valueInputOption' => 'Raw']
  );
}
