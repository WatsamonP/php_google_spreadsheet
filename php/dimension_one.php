<?php
require_once "config.php";
require_once "php/overview.php";
require_once "constants/word.php";

/***************** */
// CALCULATE RATIO //
/***************** */
$ratio = [];
$sumBasinArea = 0;
foreach ($provincesData as $key => $province) {
  $sumBasinArea += $province[$string_basinArea];
  $ratio[$key] = $province[$string_basinArea] / $province[$string_provinceArea];
}

/********** */
// GET DATA //
/********* */
$range = "WA";
$response = $service->spreadsheets_values->get($spreadsheetId, $range);
$array = $response->getValues();

if (empty($array)) {
  echo "<p>No data Found</p>";
} else {
  foreach ($array[0] as $i => $item) { // Find key
    $columnKey[$i] = $item;
  }
  foreach ($array as $r => $row) {   // Construct data
    if ($r !== 0) {
      foreach ($columnKey as $k => $key) {
        $data[$r - 1][$key] = $row[$k];
      }
    }
  }

  // Finding WAxx
  $WA_DIMEN = array_unique(array_column($data, 'dimen'));
  $WA_GROUP = array_unique(array_column($data, 'subgroup'));
  $WA_SET = [];
  $YEAR_RANGE = [];
  foreach ($WA_DIMEN as $dimen) {
    foreach ($WA_GROUP as $group) {
      foreach ($data as $row) {
        if ($row['dimen'] == $dimen) {
          if ($row['subgroup'] == $group) {
            foreach (array_keys($row) as $col) {
              if (startsWithNumber($col)) {
                $WA_SET[$dimen][$group]['data'][$row['id']]['table'][$col] = $row[$col];
                $YEAR_RANGE[$col] = $col;
              } else if ($col == 'key') {
                $WA_SET[$dimen][$group]['key'] = $row[$col];
              } else if ($col == 'unit') {
                $WA_SET[$dimen][$group]['unit'] = $row[$col];
              } else {
                $WA_SET[$dimen][$group]['data'][$row['id']][$col] = $row[$col];
              }
            }
          }
        }
      }
    }
  }

  // CALCULATION
  foreach ($WA_SET as $dKey => $dimen) {
    foreach ($dimen as $gKey => $group) {
      $index = 0;
      foreach ($group['data'] as $iKey => $item) {
        $row = $item['table'];
        foreach ($row as $key => $cell) {
          $cal = (float) $cell * (float) $ratio[$index];
          $WA_SET[$dKey][$gKey]['data'][$iKey]['cal_table'][$key] = $cal;
        }
        $index++;
      }
    }
  }
}

// print_r($WA_SET);



/*********************************** */
// GET SPECAIL INPUT FOR CALCULATION //
/*********************************** */
$range = "UserInput";
$responseU = $service->spreadsheets_values->get($spreadsheetId, $range);
$arrayU = $responseU->getValues();
if (empty($arrayU)) {
  echo "<p>No data Found</p>";
} else {
  foreach ($arrayU[0] as $i => $item) { // Find key
    $columnSIKey[$i] = $item;
  }
  foreach ($arrayU as $r => $row) {   // Construct data
    if ($r !== 0) {
      foreach ($columnSIKey as $k => $key) {
        $RawUserInput[$r - 1][$key] = $row[$k];
        if (startsWithNumber($key)) {
          $UserInput[$r - 1][$key] = $row[$k];
        }
      }
    }
  }
}

/*********************** */
// SECOND TABLE FOR WA11 //
/*********************** */
$surfaceRunoff = [];
foreach ($UserInput[0] as $i => $item) {
  // echo $item." * ".$sumBasinArea." * ".$UserInput[1][$i]." * ".pow(10, -3)."\n";
  $surfaceRunoff[$i] = $item * $sumBasinArea * $UserInput[1][$i] * pow(10, -3);
}
$population_wa11 = sumColumn($WA_SET, 'WA1', 'WA11');
$waterAvail = [];
foreach ($surfaceRunoff as $key => $item) {
  $waterAvail[$key] = $item * pow(10, 6) / $population_wa11[$key];
}

$WA11_TB = array(
  "runoffCoeff" => array('key' => "Runoff Coeff.", 'table' => $UserInput[0]),
  "basinArea" => array('key' => 'Basin Area', 'table' => array_fill(0, sizeof($YEAR_RANGE), $sumBasinArea)),
  "surfaceRunoff" => array('key' => 'Surface Runoff', 'table' => $surfaceRunoff),
  "population" => array('key' => "Population", 'table' => $population_wa11),
  "waterAvail" => array('key' => 'Water Availability (WA1)', 'table' => $waterAvail),
  "score" => array('key' => "Score", 'table' => getScore($waterAvail))
);

/*********************** */
// SECOND TABLE FOR WA12 //
/*********************** */
$groundwaterConsumption = sumColumn($WA_SET, 'WA1', "WA12");
$groundwaterAvail = [];
foreach ($groundwaterConsumption as $key => $item) {
  $groundwaterAvail[$key] = $item / $population_wa11[$key];
}

$WA12_TB = array(
  "groundwaterConsumption" => array('key' => "Groundwater Consumption", 'table' => $groundwaterConsumption),
  "population" => array('key' => 'Population', 'table' => $population_wa11),
  "groundwaterAvail" => array('key' => 'Groundwater Availability (WA2)', 'table' => $groundwaterAvail),
  "score" => array('key' => "Score", 'table' => getScore($groundwaterAvail))
);

/********** */
// WEIGH    //
/********** */
$rangeSheetKeys = "SheetKeys";
$responseSK = $service->spreadsheets_values->get($spreadsheetId, $rangeSheetKeys);
$arraySK = $responseSK->getValues();
if (empty($arraySK)) {
  echo "<p>No data Found</p>";
} else {
  foreach ($arraySK[0] as $i => $item) { // Find key
    $columnSKKey[$i] = $item;
  }
  foreach ($arraySK as $r => $row) {   // Construct data
    if ($r !== 0) {
      foreach ($columnSKKey as $k => $key) {
        // print_r($row[0]);
        $SheetKeysData[$row[0]][$key] = $row[$k];
      }
    }
  }
}

$WA11_Weight = $SheetKeysData['WA11']['weight'];
$WA12_Weight = $SheetKeysData['WA12']['weight'];

$WA1_SCORE = [];
foreach($WA11_TB['score']['table'] as $i=>$itemW11){
  $WA1_SCORE[$i] = $itemW11*$WA11_Weight + $WA12_TB['score']['table'][$i]*$WA12_Weight;
}

/********** */
// FUNTIONS //
/********** */
function sumColumn($dataset, $dimen, $group)
{
  $data = array();
  foreach ($dataset[$dimen][$group]['data'] as $subArray) {
    foreach ($subArray['cal_table'] as $key => $value) {
      $data[$key] = isset($data[$key]) ?  $value + $data[$key] : $value;
    }
  }
  return $data;
}

function startsWithNumber($string)
{
  return strlen($string) > 0 && ctype_digit(substr($string, 0, 1));
}

function getScore($array)
{
  $score = [];
  foreach ($array as $key => $value) {
    $score[$key] = scoreRate($value);
  }
  return $score;
}
function scoreRate($value)
{
  if ($value < 500) {
    return 1;
  } else if ($value < 800) {
    return 2;
  } else if ($value < 1000) {
    return 3;
  } else if ($value < 1700) {
    return 4;
  } else if ($value > 1700) {
    return 5;
  }
}
