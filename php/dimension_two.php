<?php
require_once __DIR__ . "./../config.php";
require_once __DIR__ . "./../php/overview.php";
require_once __DIR__ . "./../constants/keys.php";
require_once __DIR__ . "./../constants/word.php";
// function
require_once __DIR__ . "./../php/calculation/utils.php";
require_once __DIR__ . "./../php/calculation/getScore.php";

/***************************** */
// Pull Data for WP from sheet //
/***************************** */
$range = $WP_SHEET;
$response = $service->spreadsheets_values->get($spreadsheetId, $range);
$array = $response->getValues();

if (empty($array)) {
  echo "<p>No data Found</p>";
} else {
  foreach ($array[0] as $i => $item) { // Find key (the header)
    $columnKey[$i] = $item;
  }
  foreach ($array as $r => $row) {   // Construct data
    if ($r !== 0) {
      foreach ($columnKey as $k => $key) {
        if (isset($row[$k])) {
          $data[$r - 1][$key] = $row[$k];
        } else {
          $data[$r - 1][$key] = 0;
          include_once __DIR__ . "./../templates/alert/data_not_found.php";
        }
      }
    }
  }

  /******************************************** */
  // CONSTRUCT TABLE FOR WP1X [get GREEN TABLE] //
  /******************************************** */
  $WP_DIMEN = array_unique(array_column($data, 'dimen'));
  $WP_GROUP = array_unique(array_column($data, 'subgroup'));
  $WP_SET = []; // contains WP1, WP2, WP3, WP4 ...
  $YEAR_RANGE = [];
  foreach ($WP_DIMEN as $dimen) {
    foreach ($WP_GROUP as $group) {
      foreach ($data as $row) {
        if ($row['dimen'] == $dimen) {
          if ($row['subgroup'] == $group) {
            foreach (array_keys($row) as $col) {
              if (startsWithNumber($col)) {
                $WP_SET[$dimen][$group]['data'][$row['id']]['table'][$col] = $row[$col];
                $YEAR_RANGE[$col] = $col;
              } else if ($col == 'key') {
                $WP_SET[$dimen][$group]['key'] = $row[$col];
              } else if ($col == 'unit') {
                $WP_SET[$dimen][$group]['unit'] = $row[$col];
              } else {
                $WP_SET[$dimen][$group]['data'][$row['id']][$col] = $row[$col];
              }
            }
          }
        }
      }
    }
  }

  /************************************************** */
  // CONSTRUCT CALCULATION TABLE FOR WP1X [RED TABLE] //
  /************************************************** */
  foreach ($WP_SET as $dKey => $dimen) {
    foreach ($dimen as $gKey => $group) {
      $index = 0;
      foreach ($group['data'] as $iKey => $item) {
        $row = $item['table'];
        foreach ($row as $key => $cell) {
          if (isset($cell)) {
            $cal = (float) $cell * (float) $ratio[$index];
          } else {
            $cal = 0;
          }
          $WP_SET[$dKey][$gKey]['data'][$iKey]['cal_table'][$key] = $cal;
        }
        $index++;
      }
    }
  }
}

/*********************************** */
// GET SPECIAL INPUT FOR CALCULATION //
/*********************************** */
// FOR YEAR RANGE
// TODO Refactor use key instead index
$rangeSI = $SPECIFIC_INPUT_YEARS_SHEET;
$responseSI = $service->spreadsheets_values->get($spreadsheetId, $rangeSI);
$arraySI = $responseSI->getValues();
if (empty($arraySI)) {
  echo "<p>No data Found</p>";
} else {
  foreach ($arraySI[0] as $i => $item) { // Find key
    $columnSIKey[$i] = $item;
  }
  foreach ($arraySI as $r => $row) {   // Construct data
    if ($r !== 0) {
      foreach ($columnSIKey as $k => $key) {
        if (isset($row[$k])) {
          $RawSpecificInputYearsArray[$r - 1][$key] = $row[$k]; // GRAP ALL DATA
          if (startsWithNumber($key)) {
            $SpecificInputYearsArray[$r - 1][$key] = $row[$k];   // GRAP ONLY YEARS
          }
        } else {
          $RawSpecificInputYearsArray[$r - 1][$key] = 0; // GRAP ALL DATA
          if (startsWithNumber($key)) {
            $SpecificInputYearsArray[$r - 1][$key] = 0;   // GRAP ONLY YEARS
          }
          $LOCATION = $RawSpecificInputYearsArray[$r - 1]['key'];
          include __DIR__ . "./../templates/alert/data_not_found.php";
        }
      }
    }
  }
}
// Construct a new one
// Using key value instead of index
foreach ($RawSpecificInputYearsArray as $item) {
  foreach ($item as $key => $value) {
    if (isset($key)) {
      if (startsWithNumber($key)) {
        $SpecificInputYears[$item['dimen']][$item['group']][$item['id']]['table'][$key] = $value;
      } else {
        $SpecificInputYears[$item['dimen']][$item['group']][$item['id']][$key] = $value;
      }
    }
  }
}

//////////////////////////////////////////////// 
// █▀▀ ▄▀█ █░░ █▀▀ █░█ █░░ ▄▀█ ▀█▀ █ █▀█ █▄░█ //
// █▄▄ █▀█ █▄▄ █▄▄ █▄█ █▄▄ █▀█ ░█░ █ █▄█ █░▀█ //
////////////////////////////////////////////////
$FIRST_YEAR = array_slice($YEAR_RANGE, 0, 1)[0];
$arrayOf10E6 = array_fill($FIRST_YEAR, sizeof($YEAR_RANGE), pow(10, 6));

$totalAgricultureWater = divideTwoArray(sumColumnTable($WP_SET, 'WP1', 'WP11_B'), $arrayOf10E6);
$totalAquacultureWater = divideTwoArray(sumColumnTable($WP_SET, 'WP1', 'WP11_C'), $arrayOf10E6);
$totalLivestockWater = divideTwoArray(sumColumnTable($WP_SET, 'WP1', 'WP11_D'), $arrayOf10E6);
$totalWater = sumArrays(array($totalAgricultureWater, $totalAquacultureWater, $totalLivestockWater));

$agriculturalAquacultureLivestockGPP_Mil_Baht = sumColumnCal($WP_SET, 'WP1', 'WP11_A');
$agriculturalAquacultureLivestockGPP_US = divideTwoArray(multTwoArray($agriculturalAquacultureLivestockGPP_Mil_Baht, $arrayOf10E6), $SpecificInputYears['WP1']['WP11']['exchangeValueOfOneD']['table']);
$agriculturalAquacultureLivestockWater = $totalWater;
$agriculturalAquacultureLivestockGPP = divideTwoArray($agriculturalAquacultureLivestockGPP_US, multTwoArray($agriculturalAquacultureLivestockWater, $arrayOf10E6));
$agriculturalWaterProductivity = $agriculturalAquacultureLivestockGPP;
$WP11_SCORE = getScore($agriculturalWaterProductivity, $AWDO_2016_Threshold);

$WP61_TB = array(
  "score" => array('key' => "Score", 'table' => $WP11_SCORE)
);
// print_r($WP_SET['WP1']['WP11_A']);
// print_r($SpecificInputYearsArray['WP1']['WP1']);
print_r($agriculturalAquacultureLivestockGPP_US);
