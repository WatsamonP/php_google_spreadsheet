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

/***************************** */
// Pull Data for WP from sheet //
/***************************** */
$range = $WP_SHEET;
$response = $service->spreadsheets_values->get($spreadsheetId, $range);
$array = $response->getValues();
$WP_SET = getWxData($array, $ratio)["SET"];
$YEAR_RANGE = getWxData($array, $ratio)["YEAR_RANGE"];

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


//////////////////////////////////////////////// 
// █▀▀ ▄▀█ █░░ █▀▀ █░█ █░░ ▄▀█ ▀█▀ █ █▀█ █▄░█ //
// █▄▄ █▀█ █▄▄ █▄▄ █▄█ █▄▄ █▀█ ░█░ █ █▄█ █░▀█ //
////////////////////////////////////////////////
$FIRST_YEAR = array_slice($YEAR_RANGE, 0, 1)[0];
$arrayOf10E6 = array_fill($FIRST_YEAR, sizeof($YEAR_RANGE), pow(10, 6));
$arrayOf10E9 = array_fill($FIRST_YEAR, sizeof($YEAR_RANGE), pow(10, 9));

/********************** */
// SCORE TABLE FOR WP11 //
/********************** */
$totalAgricultureWater = divideTwoArray(sumColumnTable($WP_SET, 'WP1', 'WP11_B'), $arrayOf10E6);
$totalAquacultureWater = divideTwoArray(sumColumnTable($WP_SET, 'WP1', 'WP11_C'), $arrayOf10E6);
$totalLivestockWater = divideTwoArray(sumColumnTable($WP_SET, 'WP1', 'WP11_D'), $arrayOf10E6);
$totalWater = sumArrays(array($totalAgricultureWater, $totalAquacultureWater, $totalLivestockWater));

$exchangeValueOfOneDollar = $SpecificInputYears['WP1']['WP11']['exchangeValueOfOneD']['table'];
$agriculturalAquacultureLivestockGPP_Mil_Baht = sumColumnCal($WP_SET, 'WP1', 'WP11_A');
$agriculturalAquacultureLivestockGPP_US = divideTwoArray(multTwoArray($agriculturalAquacultureLivestockGPP_Mil_Baht, $arrayOf10E6), $exchangeValueOfOneDollar);
$agriculturalAquacultureLivestockWater = $totalWater;
$agriculturalAquacultureLivestockGPP = divideTwoArray($agriculturalAquacultureLivestockGPP_US, multTwoArray($agriculturalAquacultureLivestockWater, $arrayOf10E6));
$agriculturalWaterProductivity = $agriculturalAquacultureLivestockGPP;
$WP11_SCORE = getScore($agriculturalWaterProductivity, $AWDO_2016_AG_Threshold);

$WP11_TB = array(
  "score" => array('key' => "Score", 'table' => $WP11_SCORE)
);

/********************** */
// SCORE TABLE FOR WP21 //
/********************** */
$industrialGPP_Mil_Baht = sumColumnCal($WP_SET, 'WP2', 'WP21_A');
$industrialGPP_US = divideTwoArray(multTwoArray($industrialGPP_Mil_Baht, $arrayOf10E6), $exchangeValueOfOneDollar);
$industrialWater = divideTwoArray(sumColumnTable($WP_SET, 'WP2', 'WP21_B'), $arrayOf10E6);
$industrialGPP = divideTwoArray($industrialGPP_US, multTwoArray($industrialWater, $arrayOf10E6));
$industrialWaterProductivity = $industrialGPP;
$WP21_SCORE = getScore($industrialWaterProductivity, $AWDO_2016_NON_AG_Threshold);

$WP21_TB = array(
  "score" => array('key' => "Score", 'table' => $WP21_SCORE)
);

/********************** */
// SCORE TABLE FOR WP31 //
/********************** */
$summationA = [];
$summationB = [];
foreach ($RiverDamList['WP3']['WP31_A'] as $item) {
  foreach ($item['table'] as $year => $value)
    if (!isset($summationA[$year])) {
      $summationA[$year] = $value;
    } else {
      $summationA[$year] += $value;
    }
};
foreach ($RiverDamList['WP3']['WP31_B'] as $item) {
  foreach ($item['table'] as $year => $value)
    if (!isset($summationB[$year])) {
      $summationB[$year] = $value;
    } else {
      $summationB[$year] += $value;
    }
};

$waterProductivity_GWh = divideTwoArray($summationA, $summationB);
$waterProductivity_Wh = multTwoArray($waterProductivity_GWh, $arrayOf10E9);
$energyWaterProductivity = $waterProductivity_Wh;
$WP21_SCORE = getScore($energyWaterProductivity, $AWDO_2016_WATER_Threshold);

$WP31_TB = array(
  "score" => array('key' => "Score", 'table' => $WP21_SCORE)
);

/****************************** */
// PULL DATA from WEIGHT SHEET //
/****************************** */
$rangeWeightKeys = $WEIGHT_KEY_SHEET;
$responseSK = $service->spreadsheets_values->get($spreadsheetId, $rangeWeightKeys);
$arraySK = $responseSK->getValues();
$WeightKeysData = getWeightKey($arraySK);
$WP_SET = combineWeightKey($WP_SET, $WeightKeysData);

/************************************ */
// CONSTRUCT FINAL SCORE (below page) //
/************************************ */
$FINAL_SCORE_WP = array(
  'WP1' => getWeightedValue(['WP11' => $WP11_TB['score']['table']], $WeightKeysData),
  'WP2' => getWeightedValue(['WP21' => $WP21_TB['score']['table']], $WeightKeysData),
  'WP3' => getWeightedValue(['WP31' => $WP31_TB['score']['table']], $WeightKeysData),
);

$FINAL_INDICATOR_WP = getWeightedValue($FINAL_SCORE_WP, $WeightKeysData);

// print_r($RiverDamList['WP3']['WP31']['WP31_A']['table']);
