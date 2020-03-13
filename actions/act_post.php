<?php

include_once '../config.php';

if (isset($_POST['id'])) {
    $range = $_POST['range'];
    $values = [$_POST['data'][0]];

    $body = new Google_Service_Sheets_ValueRange([
        'values' => $values
    ]);

    $param = [
        'valueInputOption' => 'Raw'
    ];

    $service->spreadsheets_values->update(
        $spreadsheetId,
        $range,
        $body,
        $param
    );
}
