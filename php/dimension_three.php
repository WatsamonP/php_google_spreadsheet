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
// Pull Data for WD from sheet //
/***************************** */
$range = $WD_SHEET;
$response = $service->spreadsheets_values->get($spreadsheetId, $range);
$array = $response->getValues();
$WD_SET = getWxData($array, $ratio)["SET"];
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
$arrayOf10E6 = array_fill($FIRST_YEAR, sizeof($YEAR_RANGE), pow(10, 6));
$arrayOf100 = array_fill($FIRST_YEAR, sizeof($YEAR_RANGE), 100);

/********************** */
// SCORE TABLE FOR WD11 //
/********************** */
// NOTE THAT | Is this data should be the same as WA62
$_WA62_SUM = 0;
foreach ($SpecificInput['WA6']['WA62'] as $key => $items) {
  if ($key == 'reservoirs') {
    foreach ($items as $reservoir) {
      $_WA62_SUM += $reservoir['value'];
    }
  }
}
$reservoirCapacityPerArea =  $_WA62_SUM / $sumBasinArea;
$WD11_TB = array(
  "score" => array('key' => "Score", 'table' => getScore($reservoirCapacityPerArea, $AWDO_2016_AG_Threshold))
);

/********************** */
// SCORE TABLE FOR WD21 //
/********************** */
$totalGDPBasin = sumColumnCal($WD_SET, 'WD2', 'WD21');
$population = sumColumnCal($WA_SET, 'WA1', 'WA11');
$_WD21 = divideTwoArray($totalGDPBasin, $population);
$WD21_SCORE = getScore($_WD21, $AWDO_2016_AG_Threshold, 0.1);
$WD21_TB = array(
  "score" => array('key' => "Score", 'table' => $WD21_SCORE)
);

/********************** */
// SCORE TABLE FOR WD31 //
/********************** */
$totalWaterBorneCasesBasin = sumColumnCal($WD_SET, 'WD3', 'WD31_A');
$totalCasesBasin = sumColumnCal($WD_SET, 'WD3', 'WD31_B');
$_WD31 = divideTwoArray($totalWaterBorneCasesBasin, $totalCasesBasin);
$WD31_SCORE = getScore($_WD31, $AWDO_2016_WD_Threshold);
$WD31_TB = array(
  "score" => array('key' => "Score", 'table' => $WD31_SCORE)
);

/********************** */
// SCORE TABLE FOR WD41 //
/********************** */
$exchangeValueOfOneDollar = $SpecificInputYears['WP1']['WP11']['exchangeValueOfOneD']['table'];
$temp = divideTwoArray(sumColumnCal($WD_SET, 'WD4', 'WD41'), $exchangeValueOfOneDollar);
$totalEconomicLossBasin = divideTwoArray($temp, $arrayOf10E6);
$WD41_SCORE = getScore($totalEconomicLossBasin, $LOGIC_DEDUCTION_LARGEST);
$WD41_TB = array(
  "score" => array('key' => "Score", 'table' => $WD41_SCORE)
);

/********************** */
// SCORE TABLE FOR WD42 //
/********************** */
$totalFloodedAreaBasin = sumColumnCal($WD_SET, 'WD4', 'WD42');
$maximumFloodedArea = array_fill($FIRST_YEAR, sizeof($YEAR_RANGE), max($totalFloodedAreaBasin));
$proportionalArea = multTwoArray(divideTwoArray($totalFloodedAreaBasin, $maximumFloodedArea), $arrayOf100);
$WD42_SCORE = getScore($proportionalArea, $DAMAGE_THREDSHOLD);
$WD42_TB = array(
  "score" => array('key' => "Score", 'table' => $WD42_SCORE)
);

/********************** */
// SCORE TABLE FOR WD51 //
/********************** */
$tempWD51 = divideTwoArray(sumColumnCal($WD_SET, 'WD5', 'WD51'), $exchangeValueOfOneDollar);
$totalEconomicLossBasinWD51 = divideTwoArray($tempWD51, $arrayOf10E6);
$WD51_Threshold = [0.1, 0.2, 0.35, 1, 1];
$WD51_SCORE = getScore($totalEconomicLossBasinWD51, "ANY_MIN_HIGH", null, $WD51_Threshold);
$WD51_TB = array(
  "score" => array('key' => "Score", 'table' => $WD51_SCORE)
);

/********************** */
// SCORE TABLE FOR WD52 //
/********************** */
$totalAffectedAreaBasin = sumColumnCal($WD_SET, 'WD5', 'WD52');
$maximumAffectedArea = array_fill($FIRST_YEAR, sizeof($YEAR_RANGE), max($totalAffectedAreaBasin));
$proportionalArea_WD52 = multTwoArray(divideTwoArray($totalAffectedAreaBasin, $maximumAffectedArea), $arrayOf100);
$WD52_SCORE = getScore($proportionalArea_WD52, $DAMAGE_THREDSHOLD);
$WD52_TB = array(
  "score" => array('key' => "Score", 'table' => $WD52_SCORE)
);

/********************** */
// SCORE TABLE FOR WD61 //
/********************** */
$sdtRainfall = $SpecificInputYears['WD6']['WD61']['sdtRainfall']['table'];
$meanRainfall = $SpecificInputYears['WD6']['WD61']['meanRainfall']['table'];
$_WD61 = divideTwoArray($sdtRainfall, $meanRainfall);
$WD61_SCORE = getScore($_WD61, $COEFFICIENT_VARIATION);
$WD61_TB = array(
  "score" => array('key' => "Score", 'table' => $WD61_SCORE)
);

/****************************** */
// PULL DATA from WEIGHT SHEET //
/****************************** */
$rangeWeightKeys = $WEIGHT_KEY_SHEET;
$responseSK = $service->spreadsheets_values->get($spreadsheetId, $rangeWeightKeys);
$arraySK = $responseSK->getValues();
$WeightKeysData = getWeightKey($arraySK);
$WD_SET = combineWeightKey($WD_SET, $WeightKeysData);

/************************************ */
// CONSTRUCT FINAL SCORE (below page) //
/************************************ */
$arrayOfWD11 = array_fill($FIRST_YEAR, sizeof($YEAR_RANGE), $WD11_TB['score']['table']);
$FINAL_SCORE_WD = array(
  'WD1' => getWeightedValue(['WD11' => $arrayOfWD11], $WeightKeysData),
  'WD2' => getWeightedValue(['WD21' => $WD21_TB['score']['table']], $WeightKeysData),
  'WD3' => getWeightedValue(['WD31' => $WD31_TB['score']['table']], $WeightKeysData),
  'WD4' => calTwoSubGroup(['WD41' => $WD41_TB, 'WD42' => $WD42_TB], $WD_SET['WD4'], $WeightKeysData),
  'WD5' => calTwoSubGroup(['WD51' => $WD51_TB, 'WD52' => $WD52_TB], $WD_SET['WD5'], $WeightKeysData),
  'WD6' => getWeightedValue(['WD61' => $WD61_TB['score']['table']], $WeightKeysData),
);

$FINAL_INDICATOR_WD = getWeightedValue($FINAL_SCORE_WD, $WeightKeysData);
// print_r($FINAL_SCORE_WD);