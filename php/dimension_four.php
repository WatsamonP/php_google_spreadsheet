<?php
require_once __DIR__ . "./../config.php";
require_once __DIR__ . "./../php/overview.php";
require_once __DIR__ . "./../constants/keys.php";
require_once __DIR__ . "./../constants/word.php";
// function
require_once __DIR__ . "./../php/calculation/utils.php";
require_once __DIR__ . "./../php/calculation/getScore.php";
require_once __DIR__ . "./../php/fetch/fetchWX.php";
require_once __DIR__ . "./../php/fetch/fetchWeightKey.php";
require_once __DIR__ . "./../php/fetch/fetchSpecificInputYears.php";
require_once __DIR__ . "./../php/fetch/fetchSpecificInput.php";

/***************************** */
// Pull Data for WH from sheet //
/***************************** */
$range = $WH_SHEET;
$response = $service->spreadsheets_values->get($spreadsheetId, $range);
$array = $response->getValues();
$WH_SET = getWxData($array, $ratio)["SET"];
$YEAR_RANGE = getWxData($array, $ratio)["YEAR_RANGE"];
//
$responseWA = $service->spreadsheets_values->get($spreadsheetId, $WA_SHEET);
$arrayWA = $responseWA->getValues();
$WA_SET = getWxData($arrayWA, $ratio)["SET"];

/*********************************** */
// GET SPECIAL INPUT FOR CALCULATION //
/*********************************** */
$rangeSI = $SPECIFIC_INPUT_YEARS_SHEET;
$responseSI = $service->spreadsheets_values->get($spreadsheetId, $rangeSI);
$arraySI = $responseSI->getValues();
$SpecificInputYears = getSpecificInputYears($arraySI);
////////////////////
$rangeRD = $RIVER_DAM_LIST_SHEET;
$responseRD = $service->spreadsheets_values->get($spreadsheetId, $rangeRD);
$arrayRD = $responseRD->getValues();
$RiverDamList = getSpecificInputYears($arrayRD);
///////////////////
$rangeVS = $SPECIFIC_INPUT_SHEET;
$responseVS = $service->spreadsheets_values->get($spreadsheetId, $rangeVS);
$arrayVS = $responseVS->getValues();
$SpecificInput = getSpecificInput($arrayVS);

//////////////////////////////////////////////// 
// █▀▀ ▄▀█ █░░ █▀▀ █░█ █░░ ▄▀█ ▀█▀ █ █▀█ █▄░█ //
// █▄▄ █▀█ █▄▄ █▄▄ █▄█ █▄▄ █▀█ ░█░ █ █▄█ █░▀█ //
////////////////////////////////////////////////
$FIRST_YEAR = array_slice($YEAR_RANGE, 0, 1)[0];
$arrayOf100 = array_fill($FIRST_YEAR, sizeof($YEAR_RANGE), 100);
$arrayOf7_25 = array_fill($FIRST_YEAR, sizeof($YEAR_RANGE), 7.25);

/********************** */
// SCORE TABLE FOR WH11 //
/********************** */
$totalLength = 0;
$sumWH11 = [];
foreach ($RiverDamList['WH1']['WH11'] as $river) {
  $totalLength += $river['length'];
  foreach ($river['table'] as $year => $item) {
    if (!isset($sumWH11[$year])) {
      $sumWH11[$year] = $item * $river['length'];
    } else {
      $sumWH11[$year] += $item * $river['length'];
    }
  }
}
$totalLengthArray = array_fill($FIRST_YEAR, sizeof($YEAR_RANGE), $totalLength);
$totalWH11 = divideTwoArray($sumWH11, $totalLengthArray);
$DO_Percent = multTwoArray(divideTwoArray($totalWH11, $arrayOf7_25), $arrayOf100);
$WH11_SCORE = getScore($DO_Percent, "HIGH_VALUE_HIGH_SCORE", [60, 70, 90, 100]);
$WH21_SCORE = getScore($totalWH11, "LOW_VALUE_HIGH_SCORE", [2, 3, 4, 5]);
$WH11_TB = array(
  "score" => array('key' => "Score", 'table' => $WH11_SCORE)
);
$WH12_TB = array(
  "score" => array('key' => "Score", 'table' => $WH21_SCORE)
);

/********************** */
// SCORE TABLE FOR WH21 //
/********************** */
$totalTreatedWastewaterVolume = sumColumnCal($WH_SET, 'WH2', 'WH21_A');
$totalGeneratedWastewaterVolume = sumColumnCal($WH_SET, 'WH2', 'WH21_B');
$_WH21 = multTwoArray(divideTwoArray($totalTreatedWastewaterVolume, $totalGeneratedWastewaterVolume), $arrayOf100);
$WH21_SCORE = getScore($_WH21, "HIGH_VALUE_HIGH_SCORE", [60, 70, 80, 90]);
$WH21_TB = array(
  "score" => array('key' => "Score", 'table' => $WH21_SCORE)
);

/********************** */
// SCORE TABLE FOR WH31 //
/********************** */
$totalForestAreaBasin = sumColumnCal($WH_SET, 'WH3', 'WH31');
$totalAreaBasin = array_fill($FIRST_YEAR, sizeof($YEAR_RANGE), $sumBasinArea);
$_WH31 = multTwoArray(divideTwoArray($totalForestAreaBasin, $totalAreaBasin), $arrayOf100);
$WH31_SCORE = getScore($_WH31, "HIGH_VALUE_HIGH_SCORE", [10, 17, 24, 31]);
$WH31_TB = array(
  "score" => array('key' => "Score", 'table' => $WH31_SCORE)
);

/********************** */
// SCORE TABLE FOR WH32 //
/********************** */
$totalWetlandAreaBasin = sumColumnCal($WH_SET, 'WH3', 'WH32');
$_WH32 = multTwoArray(divideTwoArray($totalWetlandAreaBasin, $totalAreaBasin), $arrayOf100);
$WH32_SCORE = getScore($_WH32, "HIGH_VALUE_HIGH_SCORE", [10, 17, 24, 31]);
$WH32_TB = array(
  "score" => array('key' => "Score", 'table' => $WH32_SCORE)
);

/********************** */
// SCORE TABLE FOR WH41 //
/********************** */
$totalGeneratedWastewaterVolumeBasin = $totalGeneratedWastewaterVolume;
$totalWaterUsedBasin = sumColumnCal($WH_SET, 'WH4', 'WH41');
$_WH41 = multTwoArray(divideTwoArray($totalGeneratedWastewaterVolumeBasin, $totalWaterUsedBasin), $arrayOf100);
$WH41_SCORE = getScore($_WH41, "LOW_VALUE_HIGH_SCORE", [60, 70, 80, 90]);
$WH41_TB = array(
  "score" => array('key' => "Score", 'table' => $WH41_SCORE)
);

/********************** */
// SCORE TABLE FOR WH51 //
/********************** */
$totalAffectedAreaBasin = sumColumnCal($WH_SET, 'WH5', 'WH51');
$maximumAffectedArea = array_fill($FIRST_YEAR, sizeof($YEAR_RANGE), max($totalAffectedAreaBasin));
$proportionalArea = multTwoArray(divideTwoArray($totalAffectedAreaBasin, $maximumAffectedArea), $arrayOf100);
$WH51_SCORE = getScore($proportionalArea, "LOW_VALUE_HIGH_SCORE", [5, 10, 20, 40]);
$WH51_TB = array(
  "score" => array('key' => "Score", 'table' => $WH51_SCORE)
);

// TODO
/********************** */
// SCORE TABLE FOR WH61 //
/********************** */
$WH61_VALUE = [];
foreach ($RiverDamList['WH6']['WH61'] as $river) {
  foreach ($river['table'] as $year => $item) {
    $WH61_VALUE[$year] = $item;
  }
}
$WH61_SCORE = getScore($WH61_VALUE, "HIGH_VALUE_HIGH_SCORE", [750, 1500, 2250, 3000]);
$WH61_TB = array(
  "score" => array('key' => "Score", 'table' => $WH61_SCORE)
);

/********************** */
// SCORE TABLE FOR WH71 //
/********************** */
$populationAccessPipedWaterSupplyBasin =  sumColumnCal($WH_SET, 'WH7', 'WH71');
$totalPopulationBasin  = sumColumnCal($WA_SET, 'WA1', 'WA11');
$proportionalArea_WH71 = multTwoArray(divideTwoArray($populationAccessPipedWaterSupplyBasin, $totalPopulationBasin), $arrayOf100);
$WH71_SCORE = getScore($proportionalArea_WH71, "HIGH_VALUE_HIGH_SCORE", [60, 70, 80, 90]);
$WH71_TB = array(
  "score" => array('key' => "Score", 'table' => $WH71_SCORE)
);

/****************************** */
// PULL DATA from WEIGHT SHEET //
/****************************** */
$rangeWeightKeys = $WEIGHT_KEY_SHEET;
$responseSK = $service->spreadsheets_values->get($spreadsheetId, $rangeWeightKeys);
$arraySK = $responseSK->getValues();
$WeightKeysData = getWeightKey($arraySK);
$WH_SET = combineWeightKey($WH_SET, $WeightKeysData);

/************************************ */
// CONSTRUCT FINAL SCORE (below page) //
/************************************ */
$tempWH11 = getWeightedValue(['WH11' => $WH11_TB['score']['table']], $WeightKeysData);
$tempWH12 = getWeightedValue(['WH12' => $WH12_TB['score']['table']], $WeightKeysData);
//
$FINAL_SCORE_WH = array(
  'WH1' => addTwoArray($tempWH11, $tempWH12),
  'WH2' => getWeightedValue(['WH21' => $WH21_TB['score']['table']], $WeightKeysData),
  'WH3' => calTwoSubGroup(['WH31' => $WH31_TB, 'WH32' => $WH32_TB], $WH_SET['WH3'], $WeightKeysData),
  'WH4' => getWeightedValue(['WH41' => $WH41_TB['score']['table']], $WeightKeysData),
  'WH5' => getWeightedValue(['WH51' => $WH51_TB['score']['table']], $WeightKeysData),
  'WH6' => getWeightedValue(['WH61' => $WH61_TB['score']['table']], $WeightKeysData),
  'WH7' => getWeightedValue(['WH71' => $WH71_TB['score']['table']], $WeightKeysData),
);

$FINAL_INDICATOR_WH = getWeightedValue($FINAL_SCORE_WH, $WeightKeysData);
