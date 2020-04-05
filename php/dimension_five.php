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
require_once __DIR__ . "./../php/fetch/fetchRespondent.php";

/***************************** */
// Pull Data for WG from sheet //
/***************************** */
$range = $WG_SHEET;
$response = $service->spreadsheets_values->get($spreadsheetId, $range);
$array = $response->getValues();
$WG_SET = getWxData($array, $ratio)["SET"];
$YEAR_RANGE = getWxData($array, $ratio)["YEAR_RANGE"];
//
$responseWA = $service->spreadsheets_values->get($spreadsheetId, $WA_SHEET);
$arrayWA = $responseWA->getValues();
$WA_SET = getWxData($arrayWA, $ratio)["SET"];

$rangeRespondent = $RESPONDENT_LIST_SHEET;
$responseRespondent = $service->spreadsheets_values->get($spreadsheetId, $rangeRespondent);
$arrayRespondent = $responseRespondent->getValues();
$RespondentList = getRespondentList($arrayRespondent);

//////////////////////////////////////////////// 
// █▀▀ ▄▀█ █░░ █▀▀ █░█ █░░ ▄▀█ ▀█▀ █ █▀█ █▄░█ //
// █▄▄ █▀█ █▄▄ █▄▄ █▄█ █▄▄ █▀█ ░█░ █ █▄█ █░▀█ //
////////////////////////////////////////////////
$FIRST_YEAR = array_slice($YEAR_RANGE, 0, 1)[0];
// $arrayOf10E6 = array_fill($FIRST_YEAR, sizeof($YEAR_RANGE), pow(10, 6));
$arrayOf100 = array_fill($FIRST_YEAR, sizeof($YEAR_RANGE), 100);

/********************** */
// SCORE TABLE FOR WG11 //
/********************** */
$avgListWG11 = getAverageForeachQuestion($RespondentList['WG1']['WG11']);
$WG11_SCORE = array_sum($avgListWG11) / count(array_filter($avgListWG11));
$WG11_TB = array(
  "score" => array('key' => "Score", 'table' => $WG11_SCORE)
);

/********************** */
// SCORE TABLE FOR WG21 //
/********************** */
$avgListWG21 = getAverageForeachQuestion($RespondentList['WG2']['WG21']);
$WG21_SCORE = array_sum($avgListWG21) / count(array_filter($avgListWG21));
$WG21_TB = array(
  "score" => array('key' => "Score", 'table' => $WG21_SCORE)
);

/********************** */
// SCORE TABLE FOR WG31 //
/********************** */
$totalPopulationLivingSlumsBasin = sumColumnCal($WG_SET, 'WG3', 'WG31');
$totalPopulationBasin  = sumColumnCal($WA_SET, 'WA1', 'WA11');
$proportionalPopulation = multTwoArray(divideTwoArray($totalPopulationLivingSlumsBasin, $totalPopulationBasin), $arrayOf100);
$WG31_SCORE = getScore($proportionalPopulation, $DAMAGE_THREDSHOLD);
$WG31_TB = array(
  "score" => array('key' => "Score", 'table' => $WG31_SCORE)
);

// TODO
/********************** */
// SCORE TABLE FOR WG41 //
/********************** */

/****************************** */
// PULL DATA from WEIGHT SHEET //
/****************************** */
$rangeWeightKeys = $WEIGHT_KEY_SHEET;
$responseSK = $service->spreadsheets_values->get($spreadsheetId, $rangeWeightKeys);
$arraySK = $responseSK->getValues();
$WeightKeysData = getWeightKey($arraySK);
$WG_SET = combineWeightKey($WG_SET, $WeightKeysData);

/************************************ */
// CONSTRUCT FINAL SCORE (below page) //
/************************************ */
$arrayOfWG11 = array_fill($FIRST_YEAR, sizeof($YEAR_RANGE), $WG11_TB['score']['table']);
$arrayOfWG21 = array_fill($FIRST_YEAR, sizeof($YEAR_RANGE), $WG21_TB['score']['table']);
$FINAL_SCORE_WG = array(
  'WG1' => $arrayOfWG11,
  'WG2' => $arrayOfWG21,
  'WG3' => getWeightedValue(['WG31' => $WG31_TB['score']['table']], $WeightKeysData),
  // 'WG4' => []
);

$FINAL_INDICATOR_WG = getWeightedValue($FINAL_SCORE_WG, $WeightKeysData);
// print_r(array_key_first($RespondentList['WG1']['WG11']));