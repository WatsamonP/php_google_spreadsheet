<?php

$input = filter_input_array(INPUT_POST);

$id = $input['id'];

// [TAB] WA
$START_ID = "E";
$START_COL = "H";

$data = [];
$i = 0;
foreach ($input as $key => $item) {
  if ($key !== 'action' && $key !== 'id') {
    $data[0][$i] = $item;
    $i++;
  }
}

if ($input["action"] == 'edit') {
  $output['id'] = $id;
  $output['sheet_id'] = 'WA';
  $output['search_id'] = $START_ID;
  $output['start_id'] = $START_COL;
  $output['data'] = $data;
}

function getNumber($num)
{
  if (is_numeric($num) == 1) {
    return TRUE;
  } else {
    return FALSE;
  }
}

function finalData($data)
{
  $stack = array();
  foreach ($data as $item) {
    array_push($stack, $item);
  }
  return $stack;
}

echo json_encode($output);
