<?php
// header('Content-Type: application/json');
// header('Accept: application/json');

// require_once "config.php";

$input = filter_input_array(INPUT_POST);

// $range = "Overview!A7:B7";
// $values = [
//   ["NongJoy", "Watsamon"]
// ];

// $body = new Google_Service_Sheets_ValueRange([
//   'values' => $values
// ]);

// $body = ['values' => $values];

// $param = [
//   'valueInputOption' => 'Raw'
// ];

// $result = $service->spreadsheets_values->update(
//   $spreadsheetId,
//   $range,
//   $body,
//   $param
// );
// Start B2:D2
$START_COL = "B";
$END_COL = "D";
$id = $input['id'];
$row = preg_replace('/[^0-9]+/', '', $id) + 1;
if ($input["action"] == 'edit') {
    $output['id'] = $id;
    $output['range'] = "Provinces!" . $START_COL . $row . ":" . $END_COL . $row;
    $output['data'] = [[$input['provinceName'], $input['provinceArea'], $input['basinArea']]];
}

// $update_field = '';
// if (isset($input['name'])) {
//   $update_field .= "name='" . $input['name'] . "'";
// } else if (isset($input['gender'])) {
//   $update_field .= "gender='" . $input['gender'] . "'";
// } else if (isset($input['address'])) {
//   $update_field .= "address='" . $input['address'] . "'";
// } else if (isset($input['age'])) {
//   $update_field .= "age='" . $input['age'] . "'";
// } else if (isset($input['designation'])) {
//   $update_field .= "designation='" . $input['designation'] . "'";
// }

// if ($update_field && $input['id']) {
// }
// }
// if ($input["action"] == 'delete') {
// }

// echo json_encode($response);
// RETURN OUTPUT

// Basic example of PHP script to handle with jQuery-Tabledit plug-in.
// Note that is just an example. Should take precautions such as filtering the input data.

//header('Content-Type: application/json');
echo json_encode($output);
