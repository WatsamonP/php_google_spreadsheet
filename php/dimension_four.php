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
$totalForestAreaBasin = sumColumnCal($WH_SET, 'WH1', 'WH11');
$totalAreaBasin = array_fill($FIRST_YEAR, sizeof($YEAR_RANGE), $sumBasinArea);
$_WH11 = multTwoArray(divideTwoArray($totalForestAreaBasin, $totalAreaBasin), $arrayOf100);
$WH11_SCORE = getScore($_WH11, "HIGH_VALUE_HIGH_SCORE", [10, 17, 24, 31]);
$WH11_TB = array(
  "score" => array('key' => "Score", 'table' => $WH11_SCORE)
);

/********************** */
// SCORE TABLE FOR WH12 //
/********************** */
$totalWetlandAreaBasin = sumColumnCal($WH_SET, 'WH1', 'WH12');
$_WH12 = multTwoArray(divideTwoArray($totalWetlandAreaBasin, $totalAreaBasin), $arrayOf100);
$WH12_SCORE = getScore($_WH12, "HIGH_VALUE_HIGH_SCORE", [10, 17, 24, 31]);
$WH12_TB = array(
  "score" => array('key' => "Score", 'table' => $WH12_SCORE)
);

/********************** */
// SCORE TABLE FOR WH21 //
/********************** */
$WH21_VALUE = [];
foreach ($RiverDamList['WH2']['WH21'] as $river) {
  foreach ($river['table'] as $year => $item) {
    $WH21_VALUE[$year] = $item;
  }
}
$WH21_SCORE = getScore($WH21_VALUE, "HIGH_VALUE_HIGH_SCORE", [750, 1500, 2250, 3000]);
$WH21_TB = array(
  "score" => array('key' => "Score", 'table' => $WH21_SCORE)
);


/********************** */
// SCORE TABLE FOR WH31 //
/********************** */
$totalLength = 0;
$sumWH31 = [];
foreach ($RiverDamList['WH3']['WH31'] as $river) {
  $totalLength += $river['length'];
  foreach ($river['table'] as $year => $item) {
    if (!isset($sumWH31[$year])) {
      $sumWH31[$year] = $item * $river['length'];
    } else {
      $sumWH31[$year] += $item * $river['length'];
    }
  }
}
$totalLengthArray = array_fill($FIRST_YEAR, sizeof($YEAR_RANGE), $totalLength);
$totalWH31 = divideTwoArray($sumWH31, $totalLengthArray);
$DO_Percent = multTwoArray(divideTwoArray($totalWH31, $arrayOf7_25), $arrayOf100);
$WH31_SCORE = getScore($DO_Percent, "HIGH_VALUE_HIGH_SCORE", [60, 70, 90, 100]);
$WH32_SCORE = getScore($totalWH31, "LOW_VALUE_HIGH_SCORE", [2, 3, 4, 5]);
$WH31_TB = array(
  "score" => array('key' => "Score", 'table' => $WH31_SCORE)
);
$WH32_TB = array(
  "score" => array('key' => "Score", 'table' => $WH31_SCORE)
);

/********************** */
// SCORE TABLE FOR WH41 //
/********************** */
$totalTreatedWastewaterVolume = sumColumnCal($WH_SET, 'WH4', 'WH41_A');
$totalGeneratedWastewaterVolume = sumColumnCal($WH_SET, 'WH4', 'WH41_B');
$_WH41 = multTwoArray(divideTwoArray($totalTreatedWastewaterVolume, $totalGeneratedWastewaterVolume), $arrayOf100);
$WH41_SCORE = getScore($_WH41, "LOW_VALUE_HIGH_SCORE", [0, 5, 10, 15]);
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

/********************** */
// SCORE TABLE FOR WH61 //
/********************** */
$populationAccessPipedWaterSupplyBasin =  sumColumnCal($WH_SET, 'WH6', 'WH61');
$totalPopulationBasin  = sumColumnCal($WA_SET, 'WA1', 'WA11');
$proportionalArea_WH61 = multTwoArray(divideTwoArray($populationAccessPipedWaterSupplyBasin, $totalPopulationBasin), $arrayOf100);
$WH61_SCORE = getScore($proportionalArea_WH61, "HIGH_VALUE_HIGH_SCORE", [60, 70, 80, 90]);
$WH61_TB = array(
  "score" => array('key' => "Score", 'table' => $WH61_SCORE)
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
$tempWH31 = getWeightedValue(['WH31' => $WH31_TB['score']['table']], $WeightKeysData);
$tempWH32 = getWeightedValue(['WH32' => $WH32_TB['score']['table']], $WeightKeysData);
//
$FINAL_SCORE_WH = array(
  'WH1' => calTwoSubGroup(['WH11' => $WH11_TB, 'WH12' => $WH12_TB], $WH_SET['WH1'], $WeightKeysData),
  'WH2' => getWeightedValue(['WH21' => $WH21_TB['score']['table']], $WeightKeysData),
  'WH3' => addTwoArray($tempWH31, $tempWH32),
  'WH4' => getWeightedValue(['WH41' => $WH41_TB['score']['table']], $WeightKeysData),
  'WH5' => getWeightedValue(['WH51' => $WH51_TB['score']['table']], $WeightKeysData),
  'WH6' => getWeightedValue(['WH61' => $WH61_TB['score']['table']], $WeightKeysData),
);

$FINAL_INDICATOR_WH = getWeightedValue($FINAL_SCORE_WH, $WeightKeysData);
