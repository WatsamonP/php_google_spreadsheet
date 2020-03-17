<?php

include_once '../config.php';

if (isset($_POST['id'])) {
  $KEY_SEARCH = $_POST['sheet_id'] . "!" . $_POST['search_id'] . ":" . $_POST['search_id'];
  $responseSearch = $service->spreadsheets_values->get($spreadsheetId, $KEY_SEARCH);
  $arraySearch = $responseSearch->getValues();

  $ROW_ID = 0;
  foreach ($arraySearch as $key => $item) {
    if ($item[0] == $_POST['id']) {
      $ROW_ID = $key + 1;
    }
  }

  /*************** */
  // CALL SERVICE // 
  /*************** */
  $reqRange = $_POST['sheet_id'] . "!" . $_POST['start_id'] . $ROW_ID;
  $values = $_POST['data'];

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
