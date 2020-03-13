<?php

$input = filter_input_array(INPUT_POST);

// [TAB] Provinces
// [RANGE] B2:D2
$START_COL = "B";
$END_COL = "D";

$id = $input['id'];
$row = preg_replace('/[^0-9]+/', '', $id) + 1;
if ($input["action"] == 'edit') {
    $output['id'] = $id;
    $output['range'] = "Provinces!" . $START_COL . $row . ":" . $END_COL . $row;
    $output['data'] = [[$input['provinceName'], $input['provinceArea'], $input['basinArea']]];
}

echo json_encode($output);
