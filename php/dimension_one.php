<?php
require_once __DIR__ . "./../config.php";
require_once __DIR__ . "./../php/overview.php";
require_once __DIR__ . "./../constants/keys.php";
require_once __DIR__ . "./../constants/word.php";
// function
require_once __DIR__ . "./../php/calculation/utils.php";
require_once __DIR__ . "./../php/calculation/getCalculationWA.php";
require_once __DIR__ . "./../php/calculation/getScore.php";
//
require_once __DIR__ . "./../php/fetch/fetchWX.php";
require_once __DIR__ . "./../php/fetch/fetchWeightKey.php";
require_once __DIR__ . "./../php/fetch/fetchSpecificInputYears.php";
require_once __DIR__ . "./../php/fetch/fetchSpecificInput.php";

/************************************ */
// CALCULATE RATIO AND SUM BASIN AREA //
/************************************ */
$ratio = getRatio($provincesData, $string_provinceArea, $string_basinArea);
$sumBasinArea = getSumBasinArea($provincesData, $string_basinArea);

/***************************** */
// Pull Data for WA from sheet //
/***************************** */
$range = $WA_SHEET;
$response = $service->spreadsheets_values->get($spreadsheetId, $range);
$array = $response->getValues();
$WA_SET = getWxData($array, $ratio)["SET"];
$YEAR_RANGE = getWxData($array, $ratio)["YEAR_RANGE"];

/*********************************** */
// GET SPECIAL INPUT FOR CALCULATION //
/*********************************** */
$rangeSI = $SPECIFIC_INPUT_YEARS_SHEET;
$responseSI = $service->spreadsheets_values->get($spreadsheetId, $rangeSI);
$arraySI = $responseSI->getValues();
$SpecificInputYears = getSpecificInputYears($arraySI);
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
$arrayOf1 = array_fill($FIRST_YEAR, sizeof($YEAR_RANGE), 1);
$arrayOf10E2 = array_fill($FIRST_YEAR, sizeof($YEAR_RANGE), pow(10, 2));
$arrayOf10E6 = array_fill($FIRST_YEAR, sizeof($YEAR_RANGE), pow(10, 6));

/********************** */
// SCORE TABLE FOR WA11 //
/********************** */
$surfaceRunoff = getSurfaceRunoff($SpecificInputYears['WA1']['WA11']['runoffCoeff']['table'], $sumBasinArea, $SpecificInputYears['WA1']['WA11']['annualAverageRainfall']['table']);
$population = sumColumnCal($WA_SET, 'WA1', 'WA11');
$waterAvailabilityWA1 = divideTwoArray(multTwoArray($surfaceRunoff, $arrayOf10E6), $population);
$WA11_SCORE = getScore($waterAvailabilityWA1, "HIGH_VALUE_HIGH_SCORE", [1000, 1450, 1600, 1700]);
$WA11_TB = array(
  "score" => array('key' => "Score", 'table' => $WA11_SCORE)
);

/********************** */
// SCORE TABLE FOR WA12 //
/********************** */
$totalAbstraction = sumColumnTable($WA_SET, 'WA1', "WA12_A");
$totalRecharge = sumColumnCal($WA_SET, 'WA1', "WA12_B");
$groundwaterAvailabilityWA2 = minusTwoArray($arrayOf1, divideTwoArray($totalAbstraction, $totalRecharge));
$WA12_SCORE = getScore($groundwaterAvailabilityWA2, "HIGH_VALUE_HIGH_SCORE", [0.2, 0.4, 0.6, 0.8]);
$WA12_TB = array(
  "score" => array('key' => "Score", 'table' => $WA12_SCORE)
);
/********************** */
// SCORE TABLE FOR WA21 //
/********************** */
$totalWaterWithdrawalThroughIBT = sumColumnCal($WA_SET, 'WA2', "WA21_A");
$totalWaterWithdrawals =  sumColumnCal($WA_SET, 'WA2', "WA21_B");
$_WA21 = multTwoArray(divideTwoArray($totalWaterWithdrawalThroughIBT, $totalWaterWithdrawals), $arrayOf10E2);
$WA21_SCORE = getScore($_WA21, "LOW_VALUE_HIGH_SCORE_EQ", [0, 10, 30, 50]);
$WA21_TB = array(
  "score" => array('key' => "Score", 'table' => $WA21_SCORE)
);

/********************** */
// SCORE TABLE FOR WA31 //
/********************** */
$totalTreatedWastewaterVolume = sumColumnCal($WA_SET, 'WA3', "WA31_A");
$totalWaterDemand = sumColumnCal($WA_SET, 'WA3', "WA31_B");
$_WA31 = multTwoArray(divideTwoArray($totalTreatedWastewaterVolume, $totalWaterDemand), $arrayOf10E2);
$WA31_SCORE = getScore($_WA31, "HIGH_VALUE_HIGH_SCORE", [12, 26, 40, 64]);
$WA31_TB = array(
  "score" => array('key' => "Score", 'table' => $WA31_SCORE)
);

/********************** */
// SCORE TABLE FOR WA41 //
/********************** */
$totalFreshwaterConsumption = sumColumnCal($WA_SET, 'WA4', "WA41_A");
$totalAvailableFreshwater = sumColumnCal($WA_SET, 'WA4', "WA41_B");
$_WA41 = multTwoArray(divideTwoArray($totalFreshwaterConsumption, $totalAvailableFreshwater), $arrayOf10E2);
$WA41_SCORE = getScore($_WA41, "LOW_VALUE_HIGH_SCORE", [13, 23, 35, 40]);
$WA41_TB = array(
  "score" => array('key' => "Score", 'table' => $WA41_SCORE)
);

/********************** */
// SCORE TABLE FOR WA51 //
/********************** */
$totalImportedWaterVolume = sumColumnCal($WA_SET, 'WA5', "WA51");
$_WA51 = multTwoArray(divideTwoArray($totalImportedWaterVolume, $totalAvailableFreshwater), $arrayOf10E2);
$WA51_SCORE = getScore($_WA51, "LOW_VALUE_HIGH_SCORE_EQ", [0, 10, 30, 50]);
$WA51_TB = array(
  "score" => array('key' => "Score", 'table' => $WA51_SCORE)
);

/********************** */
// SCORE TABLE FOR WA61 //
/********************** */
$totalHarvestedRainwaterInTheBasin = sumColumnCal($WA_SET, 'WA6', "WA61");
$_WA61 = multTwoArray(divideTwoArray($totalHarvestedRainwaterInTheBasin, $totalAvailableFreshwater), $arrayOf10E2);
$WA61_SCORE = getScore($_WA61,  "HIGH_VALUE_HIGH_SCORE", [12, 26, 40, 64]);
$WA61_TB = array(
  "score" => array('key' => "Score", 'table' => $WA61_SCORE)
);

/****************************** */
// PULL DATA from WEIGHT SHEET //
/****************************** */
$rangeWeightKeys = $WEIGHT_KEY_SHEET;
$responseSK = $service->spreadsheets_values->get($spreadsheetId, $rangeWeightKeys);
$arraySK = $responseSK->getValues();
$WeightKeysData = getWeightKey($arraySK);
$WA_SET = combineWeightKey($WA_SET, $WeightKeysData);

/************************************ */
// CONSTRUCT FINAL SCORE (below page) //
/************************************ */
$tempWA11 = getWeightedValue(['WA11' => $WA11_TB['score']['table']], $WeightKeysData);
$tempWA12 = getWeightedValue(['WA12' => $WA12_TB['score']['table']], $WeightKeysData);
//
$FINAL_SCORE_WA = array(
  'WA1' => addTwoArray($tempWA11, $tempWA12),
  'WA2' => getWeightedValue(['WA21' => $WA21_TB['score']['table']], $WeightKeysData),
  'WA3' => getWeightedValue(['WA31' => $WA31_TB['score']['table']], $WeightKeysData),
  'WA4' => getWeightedValue(['WA41' => $WA41_TB['score']['table']], $WeightKeysData),
  'WA5' => getWeightedValue(['WA51' => $WA51_TB['score']['table']], $WeightKeysData),
  'WA6' => getWeightedValue(['WA61' => $WA61_TB['score']['table']], $WeightKeysData)
);

$FINAL_INDICATOR_WA = getWeightedValue($FINAL_SCORE_WA, $WeightKeysData);
