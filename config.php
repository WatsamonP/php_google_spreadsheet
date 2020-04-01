<?php

require __DIR__ . '/vendor/autoload.php';

$client = new \Google_Client();
$client->setApplicationName('Google Sheets and PHP');
$client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
$client->setAccessType('offline');
$client->setAuthConfig(__DIR__ . '/credentials.json');
$service = new Google_Service_Sheets($client);
$spreadsheetId = "19gKi4aa3UXDxO2bMDHgeGnzHs6947CW-f9a2PYT1QKs";


// $range = "TESTWA!1:1";
// $response = $service->spreadsheets_values->get($spreadsheetId, $range);
// $array = $response->getValues();

// $ROW_ID = 0;
// foreach ($array as $key => $item) {
//   if ($item[0] == 'WA11_1') {
//     $ROW_ID = $key + 1;
//   }
// }



// =CONCAT("H:",MATCH("WA",A:A),)
// CONCAT(CONCAT("R",MATCH("WA",A:A)),":",CONCAT("R",MATCH("WA",A:A)))
// // $range = "WA!CONCAT(CONCAT(\"H\", MATCH(\"WA11_1\", E:E)), \":\", CONCAT(\"R\", MATCH(\"WA11_1\", E:E)))";
// $range = "WA!H" . $ROW_ID;
// // $range = "WA!H.";
// // // $range = "WA!CONCAT('H',MATCH(WA11_1,E:E)):CONCAT('R',MATCH(WA11_1,E:E))";
// $range = "TESTWA!1:1";
// // // // $range = "WA!H2:R2";
// $values = [[0, 0]];

// $body = new Google_Service_Sheets_ValueRange([
//   'values' => $values
// ]);

// $param = [
//   'valueInputOption' => 'Raw'
// ];

// $result = $service->spreadsheets_values->update(
//   $spreadsheetId,
//   $range,
//   $body,
//   $param
// );

// $values = [[0, 0, 0, 0, 0]];
// print_r($values);
